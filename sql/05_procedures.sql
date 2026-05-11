-- =========================================================
-- MENU
-- =========================================================

-- II. PROCEDURE
--   1. room_for_multiple_pets                  -- Thủ tục kiểm tra khả năng xếp nhiều thú cưng vào cùng một phòng trống
--   2. sp_validate_and_execute_stock           -- Thủ tục kiểm tra tồn kho và thực hiện trừ kho vật tư tiêu hao
--   3. sp_refund_service_stock                 -- Thủ tục hoàn trả vật tư vào kho khi dịch vụ bị hủy
--   4. sp_assign_pet_to_room                   -- Thủ tục gán thú cưng vào một phòng cụ thể
--   5. update_orders_status                    -- Thủ tục cập nhật trạng thái hóa đơn theo số tiền đã thanh toán
--   6. sp_create_order_from_booking            -- Thủ tục tạo hóa đơn từ booking
--   7. sp_add_payment                          -- Thủ tục ghi nhận thanh toán cho hóa đơn
--   8. sp_update_booking_service_status        -- Thủ tục cập nhật trạng thái dịch vụ đặt thêm
--   9. sp_check_in_booking                     -- Thủ tục check-in booking lưu trú
--  10. sp_check_out_booking                    -- Thủ tục check-out booking lưu trú

-- =========================================================
-- II. PROCEDURE
-- =========================================================

-- 1. Kiểm tra khi một khách hàng muốn gửi nhiều thú cưng vào cùng một phòng trống
CREATE OR REPLACE PROCEDURE room_for_multiple_pets (
    p_booking_room_id IN booking_room.booking_room_id%TYPE,
    p_pet_count       IN NUMBER,
    p_max_pet_weight  IN NUMBER
)
AS
    v_max_pets        type_room.max_pets%TYPE;
    v_max_weight_kg   type_room.max_weight_kg%TYPE;
    v_existing_count  NUMBER;
BEGIN
    SELECT tr.max_pets, tr.max_weight_kg
    INTO v_max_pets, v_max_weight_kg
    FROM type_room tr
    JOIN room r
        ON r.type_room_id = tr.type_room_id
    JOIN booking_room br
        ON br.room_id = r.room_id
    WHERE br.booking_room_id = p_booking_room_id;

    SELECT COUNT(*)
    INTO v_existing_count
    FROM booking_room_pet brp
    WHERE brp.booking_room_id = p_booking_room_id;

    IF v_existing_count > 0 THEN
        RAISE_APPLICATION_ERROR(
            -20051,
            'Room is not empty. This procedure only applies to empty rooms.'
        );
    END IF;

    IF p_pet_count IS NULL OR p_pet_count <= 0 THEN
        RAISE_APPLICATION_ERROR(
            -20052,
            'Pet count must be greater than zero.'
        );
    END IF;

    IF p_pet_count > v_max_pets THEN
        RAISE_APPLICATION_ERROR(
            -20053,
            'The number of pets exceeds the room capacity. Max pets allowed = ' || v_max_pets
        );
    END IF;

    IF v_max_weight_kg IS NOT NULL
       AND p_max_pet_weight IS NOT NULL
       AND p_max_pet_weight > v_max_weight_kg THEN
        RAISE_APPLICATION_ERROR(
            -20055,
            'One or more pets exceed the room weight limit. Max weight allowed = ' || v_max_weight_kg || ' kg'
        );
    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20056,
            'Booking room does not exist.'
        );
END;
/

-- 2. Kiểm tra tồn kho và thực hiện trừ kho vật tư tiêu hao
CREATE OR REPLACE PROCEDURE sp_validate_and_execute_stock (
    p_booking_id IN booking.booking_id%TYPE,
    p_service_id IN services.service_id%TYPE,
    p_pet_id     IN pet.pet_id%TYPE
)
IS
    v_branch_id        booking.branch_id%TYPE;
    v_weight_kg        pet.weight_kg%TYPE;
    v_pet_species      pet.species%TYPE;
    v_service_species  services.species%TYPE;
    v_stock            NUMBER;
    v_usage_conv       NUMBER;
BEGIN
    SELECT b.branch_id
    INTO v_branch_id
    FROM booking b
    WHERE b.booking_id = p_booking_id;

    SELECT p.weight_kg, UPPER(p.species)
    INTO v_weight_kg, v_pet_species
    FROM pet p
    WHERE p.pet_id = p_pet_id;

    SELECT UPPER(s.species)
    INTO v_service_species
    FROM services s
    WHERE s.service_id = p_service_id;

    IF v_pet_species <> v_service_species THEN
        RAISE_APPLICATION_ERROR(
            -20033,
            'This service is not applicable to species: ' || v_pet_species
        );
    END IF;

    FOR rec IN (
        SELECT sps.product_id,
               sps.usage_amount,
               sps.usage_unit
        FROM service_product_standard sps
        WHERE sps.service_id = p_service_id
          AND UPPER(sps.species) = v_pet_species
          AND v_weight_kg >= sps.min_weight_kg
          AND v_weight_kg <= sps.max_weight_kg
    ) LOOP
        v_usage_conv := fn_convert_unit(rec.usage_amount, rec.usage_unit);

        BEGIN
            SELECT bi.quantity_in_stock
            INTO v_stock
            FROM branch_inventory bi
            WHERE bi.product_id = rec.product_id
              AND bi.branch_id = v_branch_id
            FOR UPDATE;
        EXCEPTION
            WHEN NO_DATA_FOUND THEN
                v_stock := 0;
        END;

        IF v_stock < v_usage_conv THEN
            RAISE_APPLICATION_ERROR(
                -20030,
                'Insufficient stock for product_id = ' || rec.product_id ||
                '. Required = ' || v_usage_conv || ', Available = ' || v_stock
            );
        END IF;

        UPDATE branch_inventory bi
        SET bi.quantity_in_stock = bi.quantity_in_stock - v_usage_conv,
            bi.last_updated      = SYSTIMESTAMP
        WHERE bi.branch_id = v_branch_id
          AND bi.product_id = rec.product_id;
    END LOOP;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20031,
            'Invalid booking, service, or pet information.'
        );
END;
/

-- 3. Hoàn trả vật tư vào kho khi dịch vụ bị hủy
CREATE OR REPLACE PROCEDURE sp_refund_service_stock (
    p_booking_id IN booking.booking_id%TYPE,
    p_service_id IN services.service_id%TYPE,
    p_pet_id     IN pet.pet_id%TYPE
)
IS
    v_branch_id    booking.branch_id%TYPE;
    v_weight_kg    pet.weight_kg%TYPE;
    v_pet_species  pet.species%TYPE;
    v_usage_conv   NUMBER;
    v_dummy_stock  NUMBER;
BEGIN
    SELECT b.branch_id
    INTO v_branch_id
    FROM booking b
    WHERE b.booking_id = p_booking_id;

    SELECT p.weight_kg, UPPER(p.species)
    INTO v_weight_kg, v_pet_species
    FROM pet p
    WHERE p.pet_id = p_pet_id;

    FOR rec IN (
        SELECT sps.product_id,
               sps.usage_amount,
               sps.usage_unit
        FROM service_product_standard sps
        WHERE sps.service_id = p_service_id
          AND UPPER(sps.species) = v_pet_species
          AND v_weight_kg >= sps.min_weight_kg
          AND v_weight_kg <= sps.max_weight_kg
    ) LOOP
        v_usage_conv := fn_convert_unit(rec.usage_amount, rec.usage_unit);

        BEGIN
            SELECT bi.quantity_in_stock
            INTO v_dummy_stock
            FROM branch_inventory bi
            WHERE bi.product_id = rec.product_id
              AND bi.branch_id = v_branch_id
            FOR UPDATE;
        EXCEPTION
            WHEN NO_DATA_FOUND THEN
                INSERT INTO branch_inventory (
                    branch_id,
                    product_id,
                    quantity_in_stock,
                    reorder_point,
                    last_updated
                )
                VALUES (
                    v_branch_id,
                    rec.product_id,
                    0,
                    0,
                    SYSTIMESTAMP
                );
        END;

        UPDATE branch_inventory bi
        SET bi.quantity_in_stock = bi.quantity_in_stock + v_usage_conv,
            bi.last_updated      = SYSTIMESTAMP
        WHERE bi.branch_id = v_branch_id
          AND bi.product_id = rec.product_id;
    END LOOP;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20032,
            'Invalid booking or pet information.'
        );
END;
/

-- 4. Gán thú cưng vào một phòng cụ thể
CREATE OR REPLACE PROCEDURE sp_assign_pet_to_room (
    p_booking_room_id IN booking_room.booking_room_id%TYPE,
    p_pet_id          IN pet.pet_id%TYPE
)
IS
    v_current_pets NUMBER;
    v_max_pets     type_room.max_pets%TYPE;
BEGIN
    SELECT COUNT(*)
    INTO v_current_pets
    FROM booking_room_pet brp
    WHERE brp.booking_room_id = p_booking_room_id;

    SELECT tr.max_pets
    INTO v_max_pets
    FROM booking_room br
    JOIN room r
        ON br.room_id = r.room_id
    JOIN type_room tr
        ON r.type_room_id = tr.type_room_id
    WHERE br.booking_room_id = p_booking_room_id;

    IF v_current_pets >= v_max_pets THEN
        RAISE_APPLICATION_ERROR(
            -20041,
            'Room capacity has been reached. Max pets allowed = ' || v_max_pets
        );
    END IF;

    INSERT INTO booking_room_pet (
        booking_room_id,
        pet_id,
        assigned_at
    )
    VALUES (
        p_booking_room_id,
        p_pet_id,
        SYSTIMESTAMP
    );

EXCEPTION
    WHEN DUP_VAL_ON_INDEX THEN
        RAISE_APPLICATION_ERROR(
            -20042,
            'This pet has already been assigned to this booking room.'
        );
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20043,
            'Booking room does not exist.'
        );
END;
/

-- 5. Cập nhật trạng thái hóa đơn dựa trên tổng tiền đã thanh toán
CREATE OR REPLACE PROCEDURE update_orders_status (
    p_order_id IN orders.order_id%TYPE
)
IS
    v_total_paid   NUMBER;
    v_grand_total  orders.grand_total%TYPE;
BEGIN
    SELECT ord.grand_total
    INTO v_grand_total
    FROM orders ord
    WHERE ord.order_id = p_order_id;

    SELECT NVL(SUM(p.amount), 0)
    INTO v_total_paid
    FROM payments p
    WHERE p.order_id = p_order_id
      AND p.status = 'SUCCESS';

    IF v_total_paid > v_grand_total THEN
        RAISE_APPLICATION_ERROR(
            -20071,
            'Total successful payment exceeds the order grand total.'
        );
    END IF;

    IF fn_is_order_ready_to_pay(p_order_id) THEN
        UPDATE orders
        SET status = CASE
                        WHEN v_total_paid = 0 THEN 'PENDING'
                        WHEN v_total_paid < v_grand_total THEN 'PARTIAL'
                        WHEN v_total_paid = v_grand_total THEN 'PAID'
                     END
        WHERE order_id = p_order_id;
    ELSE
        UPDATE orders
        SET status = CASE
                        WHEN status IN ('CANCELLED', 'REFUNDED') THEN status
                        WHEN v_total_paid = 0 THEN 'PENDING'
                        ELSE 'PARTIAL'
                     END
        WHERE order_id = p_order_id;
    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20072,
            'Order does not exist.'
        );
END;
/

-- 6. Tạo hóa đơn từ booking
CREATE OR REPLACE PROCEDURE sp_create_order_from_booking (
    p_order_id       IN orders.order_id%TYPE,
    p_booking_id     IN booking.booking_id%TYPE,
    p_created_by_emp IN orders.created_by_emp%TYPE
)
IS
    v_customer_id     booking.customer_id%TYPE;
    v_branch_id       booking.branch_id%TYPE;
    v_existing_count  NUMBER;
BEGIN
    SELECT b.customer_id, b.branch_id
    INTO v_customer_id, v_branch_id
    FROM booking b
    WHERE b.booking_id = p_booking_id;

    SELECT COUNT(*)
    INTO v_existing_count
    FROM orders o
    WHERE o.booking_id = p_booking_id;

    IF v_existing_count > 0 THEN
        RAISE_APPLICATION_ERROR(
            -20201,
            'This booking already has an order.'
        );
    END IF;

    INSERT INTO orders (
        order_id,
        customer_id,
        branch_id,
        booking_id,
        created_by_emp,
        status,
        subtotal,
        grand_total,
        created_at
    )
    VALUES (
        p_order_id,
        v_customer_id,
        v_branch_id,
        p_booking_id,
        p_created_by_emp,
        'PENDING',
        0,
        0,
        SYSTIMESTAMP
    );

    INSERT INTO order_details (
        order_detail_id,
        booking_room_id,
        booking_service_id,
        order_id,
        note,
        quantity,
        unit_price,
        line_total,
        created_at
    )
    SELECT
        'OD' || SUBSTR(RAWTOHEX(SYS_GUID()), 1, 8),
        br.booking_room_id,
        NULL,
        p_order_id,
        'Room charge',
        GREATEST(
            1,
            CEIL(CAST(b.checkout_expected_at AS DATE) - CAST(b.checkin_expected_at AS DATE))
        ),
        tr.base_price_per_day,
        GREATEST(
            1,
            CEIL(CAST(b.checkout_expected_at AS DATE) - CAST(b.checkin_expected_at AS DATE))
        ) * tr.base_price_per_day,
        SYSTIMESTAMP
    FROM booking_room br
    JOIN booking b
        ON br.booking_id = b.booking_id
    JOIN room r
        ON br.room_id = r.room_id
    JOIN type_room tr
        ON r.type_room_id = tr.type_room_id
    WHERE br.booking_id = p_booking_id
      AND b.checkin_expected_at IS NOT NULL
      AND b.checkout_expected_at IS NOT NULL;

    INSERT INTO order_details (
        order_detail_id,
        booking_room_id,
        booking_service_id,
        order_id,
        note,
        quantity,
        unit_price,
        line_total,
        created_at
    )
    SELECT
        'OD' || SUBSTR(RAWTOHEX(SYS_GUID()), 1, 8),
        NULL,
        bsp.booking_service_id,
        p_order_id,
        'Service charge',
        1,
        s.base_price,
        s.base_price,
        SYSTIMESTAMP
    FROM booking_services_pet bsp
    JOIN services s
        ON bsp.service_id = s.service_id
    WHERE bsp.booking_id = p_booking_id
      AND bsp.status <> 'CANCELLED';

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20202,
            'Booking does not exist.'
        );
END;
/

-- 7. Ghi nhận thanh toán cho hóa đơn
CREATE OR REPLACE PROCEDURE sp_add_payment (
    p_payment_id      IN payments.payment_id%TYPE,
    p_order_id        IN payments.order_id%TYPE,
    p_payment_method  IN payments.payment_method%TYPE,
    p_provider        IN payments.provider%TYPE,
    p_amount          IN payments.amount%TYPE,
    p_note            IN payments.note%TYPE DEFAULT NULL
)
IS
    v_order_status  orders.status%TYPE;
    v_grand_total   orders.grand_total%TYPE;
    v_total_paid    NUMBER;
BEGIN
    SELECT o.status, o.grand_total
    INTO v_order_status, v_grand_total
    FROM orders o
    WHERE o.order_id = p_order_id
    FOR UPDATE;

    IF v_order_status = 'CANCELLED' THEN
        RAISE_APPLICATION_ERROR(
            -20210,
            'Cancelled order cannot receive payment.'
        );
    END IF;

    IF v_order_status = 'PAID' THEN
        RAISE_APPLICATION_ERROR(
            -20211,
            'This order has already been fully paid.'
        );
    END IF;

    IF p_amount <= 0 THEN
        RAISE_APPLICATION_ERROR(
            -20212,
            'Payment amount must be greater than zero.'
        );
    END IF;

    SELECT NVL(SUM(p.amount), 0)
    INTO v_total_paid
    FROM payments p
    WHERE p.order_id = p_order_id
      AND p.status = 'SUCCESS';

    IF v_total_paid + p_amount > v_grand_total THEN
        RAISE_APPLICATION_ERROR(
            -20213,
            'Payment amount exceeds the remaining order balance.'
        );
    END IF;

    INSERT INTO payments (
        payment_id,
        order_id,
        payment_method,
        provider,
        amount,
        status,
        paid_at,
        note,
        created_at,
        updated_at
    )
    VALUES (
        p_payment_id,
        p_order_id,
        p_payment_method,
        p_provider,
        p_amount,
        'SUCCESS',
        SYSTIMESTAMP,
        p_note,
        SYSTIMESTAMP,
        SYSTIMESTAMP
    );

    update_orders_status(p_order_id);

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20214,
            'Order does not exist.'
        );
END;
/

-- 8. Cập nhật trạng thái dịch vụ đặt thêm
CREATE OR REPLACE PROCEDURE sp_update_booking_service_status (
    p_booking_service_id IN booking_services_pet.booking_service_id%TYPE,
    p_new_status         IN booking_services_pet.status%TYPE,
    p_note               IN booking_services_pet.note%TYPE DEFAULT NULL
)
IS
    v_old_status booking_services_pet.status%TYPE;
BEGIN
    SELECT bsp.status
    INTO v_old_status
    FROM booking_services_pet bsp
    WHERE bsp.booking_service_id = p_booking_service_id
    FOR UPDATE;

    IF p_new_status NOT IN ('PENDING', 'SCHEDULED', 'IN_PROGRESS', 'DONE', 'CANCELLED') THEN
        RAISE_APPLICATION_ERROR(
            -20220,
            'Invalid booking service status.'
        );
    END IF;

    IF v_old_status IN ('DONE', 'CANCELLED') THEN
        RAISE_APPLICATION_ERROR(
            -20221,
            'Completed or cancelled service cannot be updated.'
        );
    END IF;

    UPDATE booking_services_pet
    SET status     = p_new_status,
        note       = NVL(p_note, note),
        updated_at = SYSTIMESTAMP
    WHERE booking_service_id = p_booking_service_id;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20222,
            'Booking service does not exist.'
        );
END;
/

-- 9. Check-in booking lưu trú
CREATE OR REPLACE PROCEDURE sp_check_in_booking (
    p_booking_id IN booking.booking_id%TYPE
)
IS
    v_status booking.status%TYPE;
BEGIN
    SELECT b.status
    INTO v_status
    FROM booking b
    WHERE b.booking_id = p_booking_id
    FOR UPDATE;

    IF v_status = 'CANCELLED' THEN
        RAISE_APPLICATION_ERROR(
            -20230,
            'Cancelled booking cannot be checked in.'
        );
    END IF;

    IF v_status = 'CHECKED_OUT' THEN
        RAISE_APPLICATION_ERROR(
            -20231,
            'Checked-out booking cannot be checked in again.'
        );
    END IF;

    IF v_status = 'CHECKED_IN' THEN
        RAISE_APPLICATION_ERROR(
            -20232,
            'Booking has already been checked in.'
        );
    END IF;

    UPDATE booking
    SET status     = 'CHECKED_IN',
        updated_at = SYSTIMESTAMP
    WHERE booking_id = p_booking_id;

    UPDATE room r
    SET r.status = 'IN_USE'
    WHERE r.room_id IN (
        SELECT br.room_id
        FROM booking_room br
        WHERE br.booking_id = p_booking_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20233,
            'Booking does not exist.'
        );
END;
/

-- 10. Check-out booking lưu trú
CREATE OR REPLACE PROCEDURE sp_check_out_booking (
    p_booking_id IN booking.booking_id%TYPE
)
IS
    v_status booking.status%TYPE;
BEGIN
    SELECT b.status
    INTO v_status
    FROM booking b
    WHERE b.booking_id = p_booking_id
    FOR UPDATE;

    IF v_status = 'CANCELLED' THEN
        RAISE_APPLICATION_ERROR(
            -20240,
            'Cancelled booking cannot be checked out.'
        );
    END IF;

    IF v_status = 'CHECKED_OUT' THEN
        RAISE_APPLICATION_ERROR(
            -20241,
            'Booking has already been checked out.'
        );
    END IF;

    IF v_status <> 'CHECKED_IN' THEN
        RAISE_APPLICATION_ERROR(
            -20242,
            'Only checked-in booking can be checked out.'
        );
    END IF;

    UPDATE booking
    SET status     = 'CHECKED_OUT',
        updated_at = SYSTIMESTAMP
    WHERE booking_id = p_booking_id;

    UPDATE room r
    SET r.status = 'AVAILABLE'
    WHERE r.room_id IN (
        SELECT br.room_id
        FROM booking_room br
        WHERE br.booking_id = p_booking_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20243,
            'Booking does not exist.'
        );
END;
/
