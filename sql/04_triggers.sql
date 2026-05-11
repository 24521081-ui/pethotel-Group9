-- =========================================================
-- MENU
-- =========================================================

-- III. TRIGGER
--   1. booking_room_no_overlap                  -- Trigger chống trùng lịch đặt phòng
--   2. employee_no_overlap                      -- Trigger ngăn nhân viên bị trùng lịch thực hiện dịch vụ
--   3. booking_service_no_overlap_same_booking  -- Trigger chống trùng thời gian dịch vụ trong cùng một booking
--   4. pet_no_overlap                           -- Trigger ngăn thú cưng bị trùng thời gian lưu trú
--   5. add_pet_same_room                        -- Trigger kiểm tra điều kiện khi thêm thú cưng vào phòng đã có sẵn thú cưng
--   6. trg_payment_time_valid                   -- Trigger kiểm tra thời điểm thanh toán hợp lệ
--   7. trg_bks_inventory_sync                   -- Trigger đồng bộ tồn kho khi thêm hoặc cập nhật dịch vụ
--   8. trg_validate_pet_room_weight             -- Trigger kiểm tra tải trọng thú cưng khi phân vào phòng
--   9. trg_payment_logic_sync                   -- Trigger đồng bộ trạng thái hóa đơn khi có thay đổi thanh toán
--  10. trg_prevent_manual_paid_status           -- Trigger ngăn cập nhật thủ công hóa đơn sang PAID khi chưa đủ điều kiện
--  11. trg_sync_order_totals                    -- Trigger tự động cập nhật subtotal và grand_total khi order_details thay đổi

-- =========================================================
-- III. TRIGGER
-- =========================================================

-- 1. Chống trùng lịch đặt phòng
CREATE OR REPLACE TRIGGER booking_room_no_overlap
BEFORE INSERT OR UPDATE ON booking_room
FOR EACH ROW
DECLARE
    v_conflict_booking_id       booking.booking_id%TYPE;
    v_conflict_booking_room_id  booking_room.booking_room_id%TYPE;
BEGIN
    SELECT br.booking_id, br.booking_room_id
    INTO v_conflict_booking_id, v_conflict_booking_room_id
    FROM booking_room br
    JOIN booking b_old
        ON br.booking_id = b_old.booking_id
    JOIN booking b_new
        ON b_new.booking_id = :NEW.booking_id
    WHERE br.room_id = :NEW.room_id
      AND br.booking_room_id <> :NEW.booking_room_id
      AND b_old.status <> 'CANCELLED'
      AND b_new.status <> 'CANCELLED'
      AND b_old.checkin_expected_at IS NOT NULL
      AND b_old.checkout_expected_at IS NOT NULL
      AND b_new.checkin_expected_at IS NOT NULL
      AND b_new.checkout_expected_at IS NOT NULL
      AND b_new.checkin_expected_at < b_old.checkout_expected_at
      AND b_new.checkout_expected_at > b_old.checkin_expected_at
      AND ROWNUM = 1;

    RAISE_APPLICATION_ERROR(
        -20001,
        'Booking room schedule overlap detected. Conflicting booking_id = ' ||
        v_conflict_booking_id || ', booking_room_id = ' || v_conflict_booking_room_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        NULL;
END;
/

-- 2. Một nhân viên không thể thực hiện hai dịch vụ cùng một lúc
CREATE OR REPLACE TRIGGER employee_no_overlap
BEFORE INSERT OR UPDATE ON booking_services_pet
FOR EACH ROW
DECLARE
    v_conflict_booking_service_id  booking_services_pet.booking_service_id%TYPE;
    v_conflict_booking_id          booking_services_pet.booking_id%TYPE;
    v_new_end_time                 TIMESTAMP(6) WITH TIME ZONE;
    v_new_duration                 services.duration_minutes%TYPE;
BEGIN
    IF :NEW.employee_id IS NULL
       OR :NEW.service_id IS NULL
       OR :NEW.scheduled_at IS NULL
       OR :NEW.status NOT IN ('SCHEDULED', 'IN_PROGRESS') THEN
        RETURN;
    END IF;

    SELECT s.duration_minutes
    INTO v_new_duration
    FROM services s
    WHERE s.service_id = :NEW.service_id;

    IF v_new_duration IS NULL THEN
        RETURN;
    END IF;

    v_new_end_time := fn_add_minutes(:NEW.scheduled_at, v_new_duration);

    SELECT bsp.booking_service_id, bsp.booking_id
    INTO v_conflict_booking_service_id, v_conflict_booking_id
    FROM booking_services_pet bsp
    JOIN services s_old
        ON bsp.service_id = s_old.service_id
    WHERE bsp.employee_id = :NEW.employee_id
      AND bsp.booking_service_id <> :NEW.booking_service_id
      AND bsp.status IN ('SCHEDULED', 'IN_PROGRESS')
      AND bsp.scheduled_at IS NOT NULL
      AND s_old.duration_minutes IS NOT NULL
      AND :NEW.scheduled_at < fn_add_minutes(bsp.scheduled_at, s_old.duration_minutes)
      AND v_new_end_time > bsp.scheduled_at
      AND ROWNUM = 1;

    RAISE_APPLICATION_ERROR(
        -20011,
        'Employee schedule overlap detected. Conflicting booking_service_id = ' ||
        v_conflict_booking_service_id || ', booking_id = ' || v_conflict_booking_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        NULL;
END;
/

-- 3. Trong cùng một booking, các dịch vụ không được trùng thời gian
CREATE OR REPLACE TRIGGER booking_service_no_overlap_same_booking
BEFORE INSERT OR UPDATE ON booking_services_pet
FOR EACH ROW
DECLARE
    v_conflict_booking_service_id  booking_services_pet.booking_service_id%TYPE;
    v_new_end_time                 TIMESTAMP(6) WITH TIME ZONE;
    v_new_duration                 services.duration_minutes%TYPE;
BEGIN
    IF :NEW.booking_id IS NULL
       OR :NEW.service_id IS NULL
       OR :NEW.scheduled_at IS NULL
       OR :NEW.status NOT IN ('SCHEDULED', 'IN_PROGRESS') THEN
        RETURN;
    END IF;

    SELECT s.duration_minutes
    INTO v_new_duration
    FROM services s
    WHERE s.service_id = :NEW.service_id;

    IF v_new_duration IS NULL THEN
        RETURN;
    END IF;

    v_new_end_time := fn_add_minutes(:NEW.scheduled_at, v_new_duration);

    SELECT bsp.booking_service_id
    INTO v_conflict_booking_service_id
    FROM booking_services_pet bsp
    JOIN services s_old
        ON bsp.service_id = s_old.service_id
    WHERE bsp.booking_id = :NEW.booking_id
      AND bsp.booking_service_id <> :NEW.booking_service_id
      AND bsp.status IN ('SCHEDULED', 'IN_PROGRESS')
      AND bsp.scheduled_at IS NOT NULL
      AND s_old.duration_minutes IS NOT NULL
      AND :NEW.scheduled_at < fn_add_minutes(bsp.scheduled_at, s_old.duration_minutes)
      AND v_new_end_time > bsp.scheduled_at
      AND ROWNUM = 1;

    RAISE_APPLICATION_ERROR(
        -20012,
        'Service schedule overlap detected within the same booking. Conflicting booking_service_id = ' ||
        v_conflict_booking_service_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        NULL;
END;
/

-- 4. Một thú cưng không được ở hai nơi lưu trú cùng lúc
CREATE OR REPLACE TRIGGER pet_no_overlap
BEFORE INSERT OR UPDATE ON booking_room_pet
FOR EACH ROW
DECLARE
    v_conflict_booking_id       booking.booking_id%TYPE;
    v_conflict_booking_room_id  booking_room.booking_room_id%TYPE;
BEGIN
    SELECT b_old.booking_id, br_old.booking_room_id
    INTO v_conflict_booking_id, v_conflict_booking_room_id
    FROM booking_room_pet brp_old
    JOIN booking_room br_old
        ON brp_old.booking_room_id = br_old.booking_room_id
    JOIN booking b_old
        ON br_old.booking_id = b_old.booking_id
    JOIN booking_room br_new
        ON br_new.booking_room_id = :NEW.booking_room_id
    JOIN booking b_new
        ON br_new.booking_id = b_new.booking_id
    WHERE brp_old.pet_id = :NEW.pet_id
      AND brp_old.booking_room_id <> :NEW.booking_room_id
      AND b_old.status <> 'CANCELLED'
      AND b_new.status <> 'CANCELLED'
      AND b_old.checkin_expected_at IS NOT NULL
      AND b_old.checkout_expected_at IS NOT NULL
      AND b_new.checkin_expected_at IS NOT NULL
      AND b_new.checkout_expected_at IS NOT NULL
      AND b_new.checkin_expected_at < b_old.checkout_expected_at
      AND b_new.checkout_expected_at > b_old.checkin_expected_at
      AND ROWNUM = 1;

    RAISE_APPLICATION_ERROR(
        -20021,
        'Pet stay overlap detected. pet_id = ' || :NEW.pet_id ||
        ' is already assigned to booking_id = ' || v_conflict_booking_id ||
        ', booking_room_id = ' || v_conflict_booking_room_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        NULL;
END;
/

-- 5. Thêm thú cưng vào phòng đã có thú cưng trước đó
CREATE OR REPLACE TRIGGER add_pet_same_room
BEFORE INSERT ON booking_room_pet
FOR EACH ROW
DECLARE
    v_max_pets        type_room.max_pets%TYPE;
    v_max_weight_kg   type_room.max_weight_kg%TYPE;
    v_exist_pet_count NUMBER;
    v_new_pet_weight  pet.weight_kg%TYPE;
    v_new_customer_id pet.customer_id%TYPE;
    v_old_customer_id pet.customer_id%TYPE;
BEGIN
    SELECT tr.max_pets, tr.max_weight_kg
    INTO v_max_pets, v_max_weight_kg
    FROM type_room tr
    JOIN room r
        ON r.type_room_id = tr.type_room_id
    JOIN booking_room br
        ON br.room_id = r.room_id
    WHERE br.booking_room_id = :NEW.booking_room_id;

    SELECT COUNT(*)
    INTO v_exist_pet_count
    FROM booking_room_pet
    WHERE booking_room_id = :NEW.booking_room_id;

    IF v_exist_pet_count + 1 > v_max_pets THEN
        RAISE_APPLICATION_ERROR(
            -20042,
            'Room capacity exceeded.'
        );
    END IF;

    SELECT customer_id, weight_kg
    INTO v_new_customer_id, v_new_pet_weight
    FROM pet
    WHERE pet_id = :NEW.pet_id;

    IF v_exist_pet_count > 0 THEN
        IF v_max_pets < 2 THEN
            RAISE_APPLICATION_ERROR(
                -20041,
                'This room type does not allow shared occupancy.'
            );
        END IF;

        SELECT p.customer_id
        INTO v_old_customer_id
        FROM booking_room_pet brp
        JOIN pet p
            ON brp.pet_id = p.pet_id
        WHERE brp.booking_room_id = :NEW.booking_room_id
          AND ROWNUM = 1;

        IF v_new_customer_id <> v_old_customer_id THEN
            RAISE_APPLICATION_ERROR(
                -20043,
                'Only pets belonging to the same owner can share a room.'
            );
        END IF;
    END IF;

    IF v_max_weight_kg IS NOT NULL
       AND v_new_pet_weight IS NOT NULL
       AND v_new_pet_weight > v_max_weight_kg THEN
        RAISE_APPLICATION_ERROR(
            -20044,
            'Pet weight exceeds the room limit.'
        );
    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20045,
            'Invalid booking room or pet information.'
        );
END;
/

-- 6. Kiểm tra thời điểm thanh toán hợp lệ
CREATE OR REPLACE TRIGGER trg_payment_time_valid
BEFORE INSERT OR UPDATE ON payments
FOR EACH ROW
DECLARE
    v_order_created_at  orders.created_at%TYPE;
BEGIN
    IF :NEW.status IN ('SUCCESS', 'REFUNDED') AND :NEW.paid_at IS NULL THEN
        RAISE_APPLICATION_ERROR(
            -20062,
            'Invalid payment data: paid_at must not be NULL when status is SUCCESS or REFUNDED.'
        );
    END IF;

    IF :NEW.status IN ('PENDING', 'FAILED') AND :NEW.paid_at IS NOT NULL THEN
        RAISE_APPLICATION_ERROR(
            -20063,
            'Invalid payment data: paid_at must be NULL when status is PENDING or FAILED.'
        );
    END IF;

    IF :NEW.paid_at IS NULL THEN
        RETURN;
    END IF;

    SELECT o.created_at
    INTO v_order_created_at
    FROM orders o
    WHERE o.order_id = :NEW.order_id;

    IF :NEW.paid_at < v_order_created_at THEN
        RAISE_APPLICATION_ERROR(
            -20061,
            'Invalid payment time: paid_at must be greater than or equal to order created_at.'
        );
    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20064,
            'Invalid payment data: the referenced order_id does not exist.'
        );
END;
/

-- 7. Tự động đồng bộ kho khi thêm hoặc cập nhật dịch vụ
CREATE OR REPLACE TRIGGER trg_bks_inventory_sync
BEFORE INSERT OR UPDATE ON booking_services_pet
FOR EACH ROW
BEGIN
    IF :NEW.booking_id IS NULL
       OR :NEW.service_id IS NULL
       OR :NEW.pet_id IS NULL THEN
        RETURN;
    END IF;

    IF INSERTING THEN
        IF :NEW.status IN ('PENDING', 'SCHEDULED', 'IN_PROGRESS', 'DONE') THEN
            sp_validate_and_execute_stock(:NEW.booking_id, :NEW.service_id, :NEW.pet_id);
        END IF;
    END IF;

    IF UPDATING THEN
        IF :OLD.status IN ('PENDING', 'SCHEDULED', 'IN_PROGRESS', 'DONE')
           AND :NEW.status = 'CANCELLED' THEN
            sp_refund_service_stock(:NEW.booking_id, :NEW.service_id, :NEW.pet_id);
        END IF;

        IF :OLD.status = 'CANCELLED'
           AND :NEW.status IN ('PENDING', 'SCHEDULED', 'IN_PROGRESS', 'DONE') THEN
            sp_validate_and_execute_stock(:NEW.booking_id, :NEW.service_id, :NEW.pet_id);
        END IF;
    END IF;
END;
/

-- 8. Kiểm tra tải trọng thú cưng khi phân vào phòng
CREATE OR REPLACE TRIGGER trg_validate_pet_room_weight
BEFORE INSERT OR UPDATE ON booking_room_pet
FOR EACH ROW
BEGIN
    IF NOT fn_check_pet_weight_limit(:NEW.pet_id, :NEW.booking_room_id) THEN
        RAISE_APPLICATION_ERROR(
            -20040,
            'Operational error: pet weight exceeds the safe limit of this room type.'
        );
    END IF;
END;
/

-- 9. Đồng bộ trạng thái hóa đơn khi có thay đổi ở payments
CREATE OR REPLACE TRIGGER trg_payment_logic_sync
FOR INSERT OR UPDATE OR DELETE ON payments
COMPOUND TRIGGER

    TYPE t_order_id_list IS TABLE OF payments.order_id%TYPE INDEX BY PLS_INTEGER;
    g_order_ids t_order_id_list;
    g_count     PLS_INTEGER := 0;

    PROCEDURE add_order_id (
        p_order_id IN payments.order_id%TYPE
    )
    IS
    BEGIN
        IF p_order_id IS NOT NULL THEN
            g_count := g_count + 1;
            g_order_ids(g_count) := p_order_id;
        END IF;
    END;

AFTER EACH ROW IS
BEGIN
    IF INSERTING THEN
        add_order_id(:NEW.order_id);

    ELSIF UPDATING THEN
        add_order_id(:OLD.order_id);
        add_order_id(:NEW.order_id);

    ELSIF DELETING THEN
        add_order_id(:OLD.order_id);
    END IF;
END AFTER EACH ROW;

AFTER STATEMENT IS
BEGIN
    FOR i IN 1 .. g_count LOOP
        update_orders_status(g_order_ids(i));
    END LOOP;
END AFTER STATEMENT;

END trg_payment_logic_sync;
/

-- 10. Ngăn cập nhật thủ công trạng thái hóa đơn sang PAID khi chưa đủ điều kiện
CREATE OR REPLACE TRIGGER trg_prevent_manual_paid_status
BEFORE UPDATE OF status ON orders
FOR EACH ROW
DECLARE
    v_booking_status             booking.status%TYPE;
    v_count_not_done_service     NUMBER;
BEGIN
    IF :NEW.status = 'PAID' AND NVL(:OLD.status, 'NULL') <> 'PAID' THEN

        SELECT b.status
        INTO v_booking_status
        FROM booking b
        WHERE b.booking_id = :NEW.booking_id;

        SELECT COUNT(*)
        INTO v_count_not_done_service
        FROM order_details od
        JOIN booking_services_pet bsp
            ON od.booking_service_id = bsp.booking_service_id
        WHERE od.order_id = :NEW.order_id
          AND bsp.status NOT IN ('DONE', 'CANCELLED');

        IF v_booking_status NOT IN ('CHECKED_OUT', 'CANCELLED')
           OR v_count_not_done_service > 0 THEN
            RAISE_APPLICATION_ERROR(
                -20050,
                'Order cannot be marked as PAID because related services or stays are not yet completed.'
            );
        END IF;
    END IF;
END;
/

-- 11. Tự động cập nhật subtotal và grand_total khi order_details thay đổi
CREATE OR REPLACE TRIGGER trg_sync_order_totals
AFTER INSERT OR UPDATE OR DELETE ON order_details
FOR EACH ROW
DECLARE
    v_old_order_id orders.order_id%TYPE;
    v_new_order_id orders.order_id%TYPE;
BEGIN
    IF INSERTING THEN
        v_new_order_id := :NEW.order_id;

        UPDATE orders o
        SET subtotal = (
                SELECT NVL(SUM(od.line_total), 0)
                FROM order_details od
                WHERE od.order_id = o.order_id
            ),
            grand_total = (
                SELECT NVL(SUM(od.line_total), 0)
                FROM order_details od
                WHERE od.order_id = o.order_id
            )
        WHERE o.order_id = v_new_order_id;

    ELSIF DELETING THEN
        v_old_order_id := :OLD.order_id;

        UPDATE orders o
        SET subtotal = (
                SELECT NVL(SUM(od.line_total), 0)
                FROM order_details od
                WHERE od.order_id = o.order_id
            ),
            grand_total = (
                SELECT NVL(SUM(od.line_total), 0)
                FROM order_details od
                WHERE od.order_id = o.order_id
            )
        WHERE o.order_id = v_old_order_id;

    ELSIF UPDATING THEN
        v_old_order_id := :OLD.order_id;
        v_new_order_id := :NEW.order_id;

        UPDATE orders o
        SET subtotal = (
                SELECT NVL(SUM(od.line_total), 0)
                FROM order_details od
                WHERE od.order_id = o.order_id
            ),
            grand_total = (
                SELECT NVL(SUM(od.line_total), 0)
                FROM order_details od
                WHERE od.order_id = o.order_id
            )
        WHERE o.order_id IN (v_old_order_id, v_new_order_id);
    END IF;
END;
/
