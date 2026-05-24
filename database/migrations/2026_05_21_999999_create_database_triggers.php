<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Lưu ý:
     * - File này tạo các trigger mức Database cho migration_v1.
     * - Không dùng DELIMITER trong Laravel migration vì DB::unprepared()
     *   gửi trực tiếp từng câu SQL qua PDO.
     * - Nên đặt file migration này chạy SAU TẤT CẢ migration tạo bảng.
     */
    public function up(): void
    {
        $this->dropTriggers();
        $this->createOrderDetailCalculationTriggers();
        $this->createOrderRecalculationTriggers();
        $this->createOrderPaidAtTrigger();
        $this->createBookingRoomPetValidationTriggers();
        $this->createBookingStatusTriggers();
        $this->createAuditTriggers();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropTriggers();
    }

    private function dropTriggers(): void
    {
        $triggers = [
            'trg_audit_users_update',
            'trg_audit_booking_insert',
            'trg_booking_prevent_cancel_completed',
            'trg_booking_set_actual_times',
            'trg_booking_room_pet_check_slot',
            'trg_booking_room_pet_check_weight',
            'trg_orders_set_paid_at',
            'trg_orders_recalc_on_detail_delete',
            'trg_orders_recalc_on_detail_update',
            'trg_orders_recalc_on_detail_insert',
            'trg_order_details_calc_before_update',
            'trg_order_details_calc_before_insert',
        ];

        foreach ($triggers as $trigger) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$trigger}");
        }
    }

    /**
     * Nhóm 1: Tự động tính line_total của order_details.
     */
    private function createOrderDetailCalculationTriggers(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_order_details_calc_before_insert
BEFORE INSERT ON order_details
FOR EACH ROW
BEGIN
    SET NEW.line_total = COALESCE(NEW.quantity, 0) * COALESCE(NEW.unit_price, 0);
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_order_details_calc_before_update
BEFORE UPDATE ON order_details
FOR EACH ROW
BEGIN
    SET NEW.line_total = COALESCE(NEW.quantity, 0) * COALESCE(NEW.unit_price, 0);
END
SQL);
    }

    /**
     * Nhóm 2: Tính lại subtotal và grand_total của orders
     * khi order_details thay đổi.
     */
    private function createOrderRecalculationTriggers(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_orders_recalc_on_detail_insert
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
    UPDATE orders
    SET
        subtotal = (
            SELECT COALESCE(SUM(line_total), 0)
            FROM order_details
            WHERE order_id = NEW.order_id
        ),
        grand_total = (
            SELECT COALESCE(SUM(line_total), 0)
            FROM order_details
            WHERE order_id = NEW.order_id
        ) - discount_amount
    WHERE order_id = NEW.order_id;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_orders_recalc_on_detail_update
AFTER UPDATE ON order_details
FOR EACH ROW
BEGIN
    UPDATE orders
    SET
        subtotal = (
            SELECT COALESCE(SUM(line_total), 0)
            FROM order_details
            WHERE order_id = NEW.order_id
        ),
        grand_total = (
            SELECT COALESCE(SUM(line_total), 0)
            FROM order_details
            WHERE order_id = NEW.order_id
        ) - discount_amount
    WHERE order_id = NEW.order_id;

    IF OLD.order_id <> NEW.order_id THEN
        UPDATE orders
        SET
            subtotal = (
                SELECT COALESCE(SUM(line_total), 0)
                FROM order_details
                WHERE order_id = OLD.order_id
            ),
            grand_total = (
                SELECT COALESCE(SUM(line_total), 0)
                FROM order_details
                WHERE order_id = OLD.order_id
            ) - discount_amount
        WHERE order_id = OLD.order_id;
    END IF;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_orders_recalc_on_detail_delete
AFTER DELETE ON order_details
FOR EACH ROW
BEGIN
    UPDATE orders
    SET
        subtotal = (
            SELECT COALESCE(SUM(line_total), 0)
            FROM order_details
            WHERE order_id = OLD.order_id
        ),
        grand_total = (
            SELECT COALESCE(SUM(line_total), 0)
            FROM order_details
            WHERE order_id = OLD.order_id
        ) - discount_amount
    WHERE order_id = OLD.order_id;
END
SQL);
    }

    /**
     * Nhóm 3: Tự động set paid_at khi đơn chuyển sang COMPLETED.
     */
    private function createOrderPaidAtTrigger(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_orders_set_paid_at
BEFORE UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status = 'COMPLETED' AND OLD.status <> 'COMPLETED' AND NEW.paid_at IS NULL THEN
        SET NEW.paid_at = NOW();
    END IF;
END
SQL);
    }

    /**
     * Nhóm 4: Bảo vệ phụ khi gán thú cưng vào phòng.
     */
    private function createBookingRoomPetValidationTriggers(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_booking_room_pet_check_weight
BEFORE INSERT ON booking_room_pet
FOR EACH ROW
BEGIN
    DECLARE v_weight DECIMAL(5,2);
    DECLARE v_weight_min DECIMAL(5,2);
    DECLARE v_weight_max DECIMAL(5,2);

    SELECT weight_kg
    INTO v_weight
    FROM pet
    WHERE pet_id = NEW.pet_id;

    SELECT tr.pet_weight_min_kg, tr.pet_weight_max_kg
    INTO v_weight_min, v_weight_max
    FROM booking_room br
    JOIN room r ON r.room_id = br.room_id
    JOIN type_room tr ON tr.type_room_id = r.type_room_id
    WHERE br.booking_room_id = NEW.booking_room_id;

    IF v_weight IS NOT NULL AND v_weight_min IS NOT NULL AND v_weight_max IS NOT NULL THEN
        IF v_weight < v_weight_min OR v_weight > v_weight_max THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Pet weight does not meet room type requirements.';
        END IF;
    END IF;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_booking_room_pet_check_slot
BEFORE INSERT ON booking_room_pet
FOR EACH ROW
BEGIN
    DECLARE v_current_count INT;
    DECLARE v_max_slot INT;

    SELECT COUNT(*)
    INTO v_current_count
    FROM booking_room_pet
    WHERE booking_room_id = NEW.booking_room_id;

    SELECT tr.max_slot
    INTO v_max_slot
    FROM booking_room br
    JOIN room r ON r.room_id = br.room_id
    JOIN type_room tr ON tr.type_room_id = r.type_room_id
    WHERE br.booking_room_id = NEW.booking_room_id;

    IF v_current_count >= v_max_slot THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Room has reached maximum pet capacity.';
    END IF;
END
SQL);
    }

    /**
     * Nhóm 5: Bảo vệ phụ cho trạng thái booking.
     */
    private function createBookingStatusTriggers(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_booking_set_actual_times
BEFORE UPDATE ON booking
FOR EACH ROW
BEGIN
    IF NEW.status = 'CHECKED_IN' AND OLD.status <> 'CHECKED_IN' AND NEW.checkin_actual_at IS NULL THEN
        SET NEW.checkin_actual_at = NOW();
    END IF;

    IF NEW.status IN ('CHECKED_OUT', 'COMPLETED')
       AND OLD.status NOT IN ('CHECKED_OUT', 'COMPLETED')
       AND NEW.checkout_actual_at IS NULL
    THEN
        SET NEW.checkout_actual_at = NOW();
    END IF;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_booking_prevent_cancel_completed
BEFORE UPDATE ON booking
FOR EACH ROW
BEGIN
    IF OLD.status = 'COMPLETED' AND NEW.status = 'CANCELLED' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot cancel a booking that is already COMPLETED.';
    END IF;
END
SQL);
    }

    /**
     * Nhóm 6: Audit cơ bản.
     * Các audit phức tạp và cần auth()->id() nên xử lý bằng Laravel Observer.
     */
    private function createAuditTriggers(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_audit_booking_insert
AFTER INSERT ON booking
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (
        table_name,
        action_type,
        row_pk,
        detail_text,
        changed_by_user_id,
        changed_at
    )
    VALUES (
        'booking',
        'INSERT',
        CAST(NEW.booking_id AS CHAR),
        CONCAT(
            'New booking created. ',
            'customer_id=', NEW.customer_id,
            ', branch_id=', NEW.branch_id,
            ', status=', NEW.status,
            ', checkin_expected_at=', COALESCE(CAST(NEW.checkin_expected_at AS CHAR), 'NULL'),
            ', checkout_expected_at=', COALESCE(CAST(NEW.checkout_expected_at AS CHAR), 'NULL')
        ),
        NULL,
        NOW()
    );
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_audit_users_update
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF OLD.role <> NEW.role OR OLD.is_active <> NEW.is_active THEN
        INSERT INTO audit_log (
            table_name,
            action_type,
            row_pk,
            detail_text,
            changed_by_user_id,
            changed_at
        )
        VALUES (
            'users',
            'UPDATE',
            CAST(NEW.id AS CHAR),
            CONCAT(
                'User permission changed. ',
                IF(OLD.role <> NEW.role,
                    CONCAT('role: ', OLD.role, ' -> ', NEW.role, '. '),
                    ''
                ),
                IF(OLD.is_active <> NEW.is_active,
                    CONCAT('is_active: ', OLD.is_active, ' -> ', NEW.is_active, '.'),
                    ''
                )
            ),
            NULL,
            NOW()
        );
    END IF;
END
SQL);
    }
};
