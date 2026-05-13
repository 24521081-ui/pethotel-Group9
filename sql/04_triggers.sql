-- =========================================================
-- FILE: 04_triggers.sql
-- HỆ THỐNG: Pet Hotel and Spa
-- MỤC ĐÍCH: Tập hợp toàn bộ Trigger đảm bảo toàn vẹn dữ liệu,
-- kiểm soát ràng buộc nghiệp vụ và tự động hoá trạng thái hệ thống.
--
-- DANH SÁCH CÁC TRIGGER:
-- ── NHÓM 1: CHỐNG TRÙNG LỊCH ──────────────────────────────────────────
-- 01. booking_room_no_overlap               : Ngăn một phòng bị đặt trùng thời gian lưu trú.
-- 02. employee_no_overlap                   : Một nhân viên không thực hiện 2 dịch vụ cùng lúc.
-- 03. booking_service_no_overlap_same_booking: Các dịch vụ trong cùng booking không trùng giờ.
-- 04. pet_no_overlap                        : Một thú cưng không ở 2 phòng cùng thời điểm.
-- ── NHÓM 2: KIỂM TRA NGHIỆP VỤ KHI THÊM DỮ LIỆU ─────────────────────
-- 21: Tự động tính toán thành tiền cho chi tiết hóa đơn
-- 05. add_pet_same_room                     : Kiểm soát ghép thú cưng (sức chứa, tải trọng, chủ sở hữu).
-- 06. trg_payment_time_valid                : Xác thực logic thời gian thanh toán hóa đơn.
-- 07. trg_validate_pet_room_weight          : Ngăn thú cưng vượt giới hạn tải trọng phòng.
-- 08. trg_check_emp_branch                  : Nhân viên dịch vụ phải thuộc chi nhánh của booking.
-- 09. trg_check_pet_owner_match             : Thú cưng phải thuộc khách hàng đặt booking.
-- ── NHÓM 3: TỰ ĐỘNG HOÁ TRẠNG THÁI ───────────────────────────────────
-- 11. trg_auto_update_room_in_use           : Phòng → IN_USE khi xếp thú cưng vào chuồng.
-- 12. trg_auto_update_room_available        : Phòng → AVAILABLE khi booking CHECKED_OUT/CANCELLED.
-- ── NHÓM 4: ĐỒNG BỘ TÀI CHÍNH ────────────────────────────────────────
-- 13. trg_sync_order_totals                 : Tự động cập nhật subtotal/grand_total khi order_details thay đổi.
-- 14. trg_apply_deposit_on_order            : Khấu trừ tiền cọc vào grand_total khi tạo order.
-- 15. trg_payment_logic_sync                : Đồng bộ trạng thái hóa đơn (PAID/PARTIAL) sau thanh toán.
-- 16. trg_prevent_manual_paid_status        : Chặn chốt hóa đơn thủ công khi dịch vụ/phòng chưa xong.
-- ── NHÓM 5: AUDIT LOG ─────────────────────────────────────────────────
-- 17. trg_payment_audit                     : Ghi log kiểm toán khi trạng thái thanh toán thay đổi.
-- 18. trg_stock_reorder_alert               : Ghi cảnh báo khi tồn kho chạm ngưỡng reorder_point.
-- ── NHÓM 6: ĐỒNG BỘ TỒN KHO & CUNG ỨNG ──────────────────────────────
-- 10. trg_bks_inventory_sync                : Trừ/hoàn tồn kho vật tư theo trạng thái dịch vụ.
-- 19: Tự động cộng tồn kho khi phiếu nhập hàng được duyệt
-- 20: Tự động đồng bộ tồn kho thực tế khi hoàn tất kiểm kho
-- =========================================================

-- =========================================================
-- CÁC BẢNG PHỤ TRỢ (tạo trước khi compile trigger)
-- =========================================================

-- Bảng ghi nhật ký thay đổi trạng thái thanh toán (dùng bởi TRG-17)
CREATE TABLE IF NOT EXISTS payment_audit_log (
    log_id         NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    payment_id     VARCHAR2(10)   NOT NULL,
    order_id       VARCHAR2(10)   NOT NULL,
    customer_id    VARCHAR2(10),
    amount         NUMBER(12,2),
    payment_method VARCHAR2(30),
    action         VARCHAR2(20)   NOT NULL,   -- SUCCESS / REFUNDED / FAILED / PENDING
    old_status     VARCHAR2(20),
    new_status     VARCHAR2(20),
    logged_at      TIMESTAMP DEFAULT SYSTIMESTAMP NOT NULL
);
/

-- Bảng ghi cảnh báo tồn kho dưới ngưỡng reorder_point (dùng bởi TRG-18)
CREATE TABLE IF NOT EXISTS stock_alert_log (
    alert_id      NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    branch_id     VARCHAR2(10)   NOT NULL,
    product_id    VARCHAR2(10)   NOT NULL,
    quantity      NUMBER         NOT NULL,
    reorder_point NUMBER,
    alerted_at    TIMESTAMP DEFAULT SYSTIMESTAMP NOT NULL
);
/

-- =========================================================
-- ── NHÓM 1: CHỐNG TRÙNG LỊCH ──────────────────────────────────────────
-- =========================================================

-- TRG-01: Chống trùng lịch đặt phòng
-- Bảng: booking_room
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Ngăn chặn việc một phòng được gán cho 2 booking khác nhau có
--   khoảng thời gian check-in và check-out bị giao nhau (overlap).
--   Logic kiểm tra: b_new.checkin < b_old.checkout AND b_new.checkout > b_old.checkin.
--   Nếu phát hiện trùng, báo lỗi kèm mã booking và booking_room xung đột.
-- =========================================================
CREATE OR REPLACE TRIGGER booking_room_no_overlap
BEFORE INSERT OR UPDATE ON booking_room
FOR EACH ROW
DECLARE
    v_conflict_booking_id      booking.booking_id%TYPE;
    v_conflict_booking_room_id booking_room.booking_room_id%TYPE;
BEGIN
    SELECT br.booking_id, br.booking_room_id
    INTO   v_conflict_booking_id, v_conflict_booking_room_id
    FROM   booking_room br
    JOIN   booking      b_old ON b_old.booking_id = br.booking_id
    JOIN   booking      b_new ON b_new.booking_id = :NEW.booking_id
    WHERE  br.room_id           = :NEW.room_id
      AND  br.booking_room_id  <> :NEW.booking_room_id   -- Tránh tự so sánh với chính nó
      AND  b_old.status        <> 'CANCELLED'
      AND  b_new.status        <> 'CANCELLED'
      AND  b_new.checkin_expected_at  < b_old.checkout_expected_at
      AND  b_new.checkout_expected_at > b_old.checkin_expected_at
      AND  ROWNUM = 1;

    RAISE_APPLICATION_ERROR(
        -20001,
        'LỖI TRÙNG PHÒNG: Phòng đã được đặt bởi booking_id = ' || v_conflict_booking_id ||
        ' (booking_room_id = ' || v_conflict_booking_room_id || ').'
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;   -- Không có xung đột → cho phép tiếp tục
END booking_room_no_overlap;
/

-- TRG-02: Kiểm soát thời gian làm việc của nhân viên
-- Bảng: booking_services_pet
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Đảm bảo 1 nhân viên không thể thực hiện 2 dịch vụ khác nhau trong
--   cùng một khoảng thời gian. Sử dụng fn_add_minutes tính thời gian
--   kết thúc dựa vào duration_minutes của dịch vụ.
--   Bỏ qua khi employee_id / service_id / scheduled_at chưa có,
--   hoặc trạng thái không cần xét (CANCELLED / DONE).
-- =========================================================
CREATE OR REPLACE TRIGGER employee_no_overlap
BEFORE INSERT OR UPDATE ON booking_services_pet
FOR EACH ROW
DECLARE
    v_conflict_bks_id booking_services_pet.booking_service_id%TYPE;
    v_conflict_bk_id  booking_services_pet.booking_id%TYPE;
    v_new_end_time    TIMESTAMP WITH TIME ZONE;
BEGIN
    -- Bỏ qua khi thiếu dữ liệu cần thiết hoặc trạng thái không xét
    IF :NEW.employee_id  IS NULL
    OR :NEW.service_id   IS NULL
    OR :NEW.scheduled_at IS NULL
    OR :NEW.status NOT IN ('SCHEDULED', 'IN_PROGRESS') THEN
        RETURN;
    END IF;

    -- Thời điểm kết thúc dịch vụ mới
    SELECT fn_add_minutes(:NEW.scheduled_at, s.duration_minutes)
    INTO   v_new_end_time
    FROM   services s
    WHERE  s.service_id = :NEW.service_id;

    -- Tìm dịch vụ nào của cùng nhân viên bị trùng giờ
    SELECT bsp.booking_service_id, bsp.booking_id
    INTO   v_conflict_bks_id, v_conflict_bk_id
    FROM   booking_services_pet bsp
    JOIN   services             s_old ON s_old.service_id = bsp.service_id
    WHERE  bsp.employee_id        = :NEW.employee_id
      AND  bsp.booking_service_id <> :NEW.booking_service_id
      AND  bsp.status             IN ('SCHEDULED', 'IN_PROGRESS')
      AND  bsp.scheduled_at       IS NOT NULL
      AND  :NEW.scheduled_at < fn_add_minutes(bsp.scheduled_at, s_old.duration_minutes)
      AND  v_new_end_time    > bsp.scheduled_at
      AND  ROWNUM = 1;

    RAISE_APPLICATION_ERROR(
        -20011,
        'LỖI LỊCH NHÂN VIÊN: Nhân viên đang thực hiện dịch vụ khác cùng giờ. ' ||
        'booking_service_id xung đột = ' || v_conflict_bks_id ||
        ', booking_id = ' || v_conflict_bk_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;
END employee_no_overlap;
/

-- TRG-03: Chống trùng lặp dịch vụ trong cùng một Booking
-- Bảng: booking_services_pet
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Trong cùng một booking, thú cưng không thể được đặt 2 dịch vụ
--   diễn ra song song nhau (cùng pet_id, cùng khoảng giờ thực hiện).
--   Giúp tránh nhầm lẫn lịch và tranh chấp nhân sự.
-- =========================================================
CREATE OR REPLACE TRIGGER booking_service_no_overlap_same_booking
BEFORE INSERT OR UPDATE ON booking_services_pet
FOR EACH ROW
DECLARE
    v_conflict_bks_id booking_services_pet.booking_service_id%TYPE;
    v_new_end_time    TIMESTAMP WITH TIME ZONE;
BEGIN
    IF :NEW.booking_id   IS NULL
    OR :NEW.service_id   IS NULL
    OR :NEW.scheduled_at IS NULL
    OR :NEW.status NOT IN ('SCHEDULED', 'IN_PROGRESS') THEN
        RETURN;
    END IF;

    SELECT fn_add_minutes(:NEW.scheduled_at, s.duration_minutes)
    INTO   v_new_end_time
    FROM   services s
    WHERE  s.service_id = :NEW.service_id;

    SELECT bsp.booking_service_id
    INTO   v_conflict_bks_id
    FROM   booking_services_pet bsp
    JOIN   services             s_old ON s_old.service_id = bsp.service_id
    WHERE  bsp.booking_id         = :NEW.booking_id
      AND  bsp.pet_id             = :NEW.pet_id           -- Cùng thú cưng
      AND  bsp.booking_service_id <> :NEW.booking_service_id
      AND  bsp.status             IN ('SCHEDULED', 'IN_PROGRESS')
      AND  bsp.scheduled_at       IS NOT NULL
      AND  :NEW.scheduled_at < fn_add_minutes(bsp.scheduled_at, s_old.duration_minutes)
      AND  v_new_end_time    > bsp.scheduled_at
      AND  ROWNUM = 1;

    RAISE_APPLICATION_ERROR(
        -20012,
        'LỖI TRÙNG LỊCH DỊCH VỤ: Thú cưng đang có dịch vụ khác cùng giờ trong booking này. ' ||
        'booking_service_id xung đột = ' || v_conflict_bks_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;
END booking_service_no_overlap_same_booking;
/

-- TRG-04: Đảm bảo vị trí duy nhất cho một thú cưng
-- Bảng: booking_room_pet
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Kiểm soát không cho phép 1 thú cưng (pet_id) được xếp vào 2 phòng
--   khác nhau trong cùng một khoảng thời gian lưu trú, kể cả giữa
--   các booking khác nhau.
-- =========================================================
CREATE OR REPLACE TRIGGER pet_no_overlap
BEFORE INSERT OR UPDATE ON booking_room_pet
FOR EACH ROW
DECLARE
    v_conflict_booking_id      booking.booking_id%TYPE;
    v_conflict_booking_room_id booking_room.booking_room_id%TYPE;
BEGIN
    SELECT b_old.booking_id, br_old.booking_room_id
    INTO   v_conflict_booking_id, v_conflict_booking_room_id
    FROM   booking_room_pet brp_old
    JOIN   booking_room     br_old ON br_old.booking_room_id = brp_old.booking_room_id
    JOIN   booking          b_old  ON b_old.booking_id       = br_old.booking_id
    JOIN   booking_room     br_new ON br_new.booking_room_id = :NEW.booking_room_id
    JOIN   booking          b_new  ON b_new.booking_id       = br_new.booking_id
    WHERE  brp_old.pet_id          = :NEW.pet_id
      AND  brp_old.booking_room_id <> :NEW.booking_room_id
      AND  b_old.status            <> 'CANCELLED'
      AND  b_new.status            <> 'CANCELLED'
      AND  b_new.checkin_expected_at  < b_old.checkout_expected_at
      AND  b_new.checkout_expected_at > b_old.checkin_expected_at
      AND  ROWNUM = 1;

    RAISE_APPLICATION_ERROR(
        -20013,
        'LỖI TRÙNG VỊ TRÍ THÚ CƯNG: Thú cưng này đã được xếp phòng trong khoảng thời gian trên. ' ||
        'booking_id xung đột = ' || v_conflict_booking_id ||
        ', booking_room_id = ' || v_conflict_booking_room_id
    );

EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;
END pet_no_overlap;
/

-- =========================================================
-- ── NHÓM 2: KIỂM TRA NGHIỆP VỤ KHI THÊM DỮ LIỆU ─────────────────────
-- =========================================================

-- TRG-05: Kiểm soát điều kiện ghép nhiều thú cưng vào cùng phòng
-- Bảng: booking_room_pet
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Khi muốn xếp thêm thú cưng vào một phòng đã có thú cưng khác,
--   kiểm tra 3 điều kiện:
--   (1) Phòng phải còn chỗ (chưa đạt max_pets).
--   (2) Thú cưng mới không vượt giới hạn tải trọng phòng (gọi fn_check_pet_weight_limit).
--   (3) Tất cả thú cưng trong phòng phải cùng chủ (cùng customer_id).
--   Trigger này bổ sung lớp kiểm tra thứ 3 (cùng chủ) so với TRG-07 (chỉ kiểm tra cân nặng).
-- =========================================================
CREATE OR REPLACE TRIGGER add_pet_same_room
BEFORE INSERT OR UPDATE ON booking_room_pet
FOR EACH ROW
DECLARE
    v_current_pets   NUMBER;
    v_max_pets       type_room.max_pets%TYPE;
    v_owner_new      pet.customer_id%TYPE;
    v_owner_existing pet.customer_id%TYPE;
BEGIN
    -- 1. Đếm số thú cưng hiện tại trong phòng
    SELECT COUNT(*)
    INTO   v_current_pets
    FROM   booking_room_pet brp
    WHERE  brp.booking_room_id = :NEW.booking_room_id;

    -- 2. Lấy sức chứa tối đa của phòng
    SELECT tr.max_pets
    INTO   v_max_pets
    FROM   booking_room br
    JOIN   room         r  ON r.room_id      = br.room_id
    JOIN   type_room    tr ON tr.type_room_id = r.type_room_id
    WHERE  br.booking_room_id = :NEW.booking_room_id;

    -- 3. Kiểm tra sức chứa
    IF v_current_pets >= v_max_pets THEN
        RAISE_APPLICATION_ERROR(
            -20041,
            'LỖI SỨC CHỨA: Phòng đã đạt giới hạn ' || v_max_pets || ' thú cưng.'
        );
    END IF;

    -- 4. Kiểm tra cân nặng (dùng hàm đã có)
    IF NOT fn_check_pet_weight_limit(:NEW.pet_id, :NEW.booking_room_id) THEN
        RAISE_APPLICATION_ERROR(
            -20042,
            'LỖI TẢI TRỌNG: Cân nặng thú cưng vượt giới hạn an toàn của loại phòng.'
        );
    END IF;

    -- 5. Kiểm tra cùng chủ (chỉ khi phòng đã có thú cưng khác)
    IF v_current_pets > 0 THEN
        -- Lấy chủ của thú cưng mới
        SELECT p.customer_id
        INTO   v_owner_new
        FROM   pet p
        WHERE  p.pet_id = :NEW.pet_id;

        -- Lấy chủ của một thú cưng đang ở trong phòng
        SELECT p.customer_id
        INTO   v_owner_existing
        FROM   booking_room_pet brp
        JOIN   pet              p ON p.pet_id = brp.pet_id
        WHERE  brp.booking_room_id = :NEW.booking_room_id
          AND  ROWNUM = 1;

        IF v_owner_new <> v_owner_existing THEN
            RAISE_APPLICATION_ERROR(
                -20043,
                'LỖI CHỦ SỞ HỮU: Chỉ cho phép ghép thú cưng của cùng một khách hàng vào một phòng.'
            );
        END IF;
    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20044,
            'LỖI DỮ LIỆU: Không tìm thấy thông tin phòng hoặc thú cưng cần kiểm tra.'
        );
END add_pet_same_room;
/

-- TRG-06: Xác thực thời gian thanh toán
-- Bảng: payments
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Đảm bảo tính hợp lệ của paid_at so với trạng thái thanh toán:
--   - status = 'SUCCESS' → paid_at bắt buộc phải có giá trị.
--   - status = 'PENDING' → paid_at bắt buộc phải NULL.
--   - paid_at (khi có) không được nhỏ hơn thời điểm tạo đơn hàng.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_payment_time_valid
BEFORE INSERT OR UPDATE ON payments
FOR EACH ROW
DECLARE
    v_order_created_at orders.created_at%TYPE;
BEGIN
    -- Quy tắc 1: Thanh toán thành công bắt buộc có ngày giờ
    IF :NEW.status = 'SUCCESS' AND :NEW.paid_at IS NULL THEN
        RAISE_APPLICATION_ERROR(
            -20062,
            'LỖI THANH TOÁN: paid_at bắt buộc phải có giá trị khi status = SUCCESS.'
        );
    END IF;

    -- Quy tắc 2: Thanh toán chờ xử lý không được điền ngày giờ
    IF :NEW.status = 'PENDING' AND :NEW.paid_at IS NOT NULL THEN
        RAISE_APPLICATION_ERROR(
            -20063,
            'LỖI THANH TOÁN: paid_at phải để NULL khi status = PENDING.'
        );
    END IF;

    -- Quy tắc 3: paid_at phải sau hoặc bằng thời điểm tạo đơn hàng
    IF :NEW.paid_at IS NOT NULL THEN
        SELECT o.created_at
        INTO   v_order_created_at
        FROM   orders o
        WHERE  o.order_id = :NEW.order_id;

        IF :NEW.paid_at < v_order_created_at THEN
            RAISE_APPLICATION_ERROR(
                -20061,
                'LỖI THANH TOÁN: paid_at không thể nhỏ hơn thời điểm tạo đơn hàng (created_at).'
            );
        END IF;
    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20064,
            'LỖI THANH TOÁN: order_id được tham chiếu không tồn tại trong hệ thống.'
        );
END trg_payment_time_valid;
/

-- TRG-07: Ràng buộc cân nặng thú cưng khi xếp phòng
-- Bảng: booking_room_pet
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Lớp kiểm tra cân nặng độc lập, sử dụng fn_check_pet_weight_limit.
--   Tách riêng khỏi TRG-05 (add_pet_same_room) để đảm bảo kiểm tra
--   cân nặng luôn được thực thi kể cả khi phòng chỉ có 1 thú cưng.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_validate_pet_room_weight
BEFORE INSERT OR UPDATE ON booking_room_pet
FOR EACH ROW
BEGIN
    IF NOT fn_check_pet_weight_limit(:NEW.pet_id, :NEW.booking_room_id) THEN
        RAISE_APPLICATION_ERROR(
            -20040,
            'LỖI VẬN HÀNH: Trọng lượng thú cưng vượt quá giới hạn an toàn của loại phòng này.'
        );
    END IF;
END trg_validate_pet_room_weight;
/

-- TRG-08: Kiểm tra nhân viên thực hiện dịch vụ phải thuộc chi nhánh booking
-- Bảng: booking_services_pet
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Tránh gán nhân viên từ chi nhánh khác thực hiện dịch vụ cho booking
--   không thuộc chi nhánh của mình. Bỏ qua nếu employee_id chưa được gán.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_check_emp_branch
BEFORE INSERT OR UPDATE ON booking_services_pet
FOR EACH ROW
DECLARE
    v_emp_branch     employee.branch_id%TYPE;
    v_booking_branch booking.branch_id%TYPE;
BEGIN
    -- Bỏ qua nếu chưa gán nhân viên
    IF :NEW.employee_id IS NULL THEN
        RETURN;
    END IF;

    SELECT e.branch_id
    INTO   v_emp_branch
    FROM   employee e
    WHERE  e.employee_id = :NEW.employee_id;

    SELECT b.branch_id
    INTO   v_booking_branch
    FROM   booking b
    WHERE  b.booking_id = :NEW.booking_id;

    IF v_emp_branch <> v_booking_branch THEN
        RAISE_APPLICATION_ERROR(
            -20021,
            'LỖI CHI NHÁNH: Nhân viên (branch_id = ' || v_emp_branch || ') ' ||
            'không thuộc chi nhánh của booking (branch_id = ' || v_booking_branch || ').'
        );
    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20022,
            'LỖI DỮ LIỆU: Không tìm thấy nhân viên hoặc booking được tham chiếu.'
        );
END trg_check_emp_branch;
/

-- TRG-09: [MỚI - TRG-13] Thú cưng phải thuộc khách hàng đặt booking
-- Bảng: booking_services_pet
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Ràng buộc chéo giữa thú cưng và khách hàng: pet.customer_id phải
--   trùng với booking.customer_id. Ngăn nhân viên vô tình đặt dịch vụ
--   cho thú cưng của khách khác vào booking không phải của chủ chúng.
--   Áp dụng cả khi UPDATE (ví dụ đổi pet_id hoặc booking_id).
-- =========================================================
CREATE OR REPLACE TRIGGER trg_check_pet_owner_match
BEFORE INSERT OR UPDATE ON booking_services_pet
FOR EACH ROW
DECLARE
    v_pet_owner      pet.customer_id%TYPE;
    v_booking_owner  booking.customer_id%TYPE;
BEGIN
    -- Lấy chủ sở hữu của thú cưng
    SELECT p.customer_id
    INTO   v_pet_owner
    FROM   pet p
    WHERE  p.pet_id = :NEW.pet_id;

    -- Lấy khách hàng đặt booking
    SELECT b.customer_id
    INTO   v_booking_owner
    FROM   booking b
    WHERE  b.booking_id = :NEW.booking_id;

    IF v_pet_owner <> v_booking_owner THEN
        RAISE_APPLICATION_ERROR(
            -20031,
            'LỖI SỞ HỮU: Thú cưng (pet_id = ' || :NEW.pet_id || ') ' ||
            'không thuộc khách hàng đặt booking này ' ||
            '(booking customer_id = ' || v_booking_owner || ').'
        );
    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(
            -20032,
            'LỖI DỮ LIỆU: Không tìm thấy thú cưng hoặc booking được tham chiếu.'
        );
END trg_check_pet_owner_match;
/

-- =========================================================
-- ── NHÓM 3: TỰ ĐỘNG HOÁ TRẠNG THÁI ───────────────────────────────────
-- =========================================================

-- TRG-10: Đồng bộ tồn kho vật tư theo trạng thái dịch vụ
-- Bảng: booking_services_pet
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   - INSERT dịch vụ mới (SCHEDULED / IN_PROGRESS / DONE) → gọi PROC-02 để trừ kho.
--   - UPDATE sang CANCELLED từ trạng thái đang thực hiện → gọi PROC-03 để hoàn kho.
--   - Khôi phục dịch vụ từ CANCELLED về trạng thái hoạt động → trừ kho lại.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_bks_inventory_sync
BEFORE INSERT OR UPDATE ON booking_services_pet
FOR EACH ROW
BEGIN
    IF INSERTING THEN
        IF :NEW.status IN ('PENDING', 'SCHEDULED', 'IN_PROGRESS', 'DONE') THEN
            sp_validate_and_execute_stock(:NEW.booking_id, :NEW.service_id, :NEW.pet_id);
        END IF;
    END IF;

    IF UPDATING THEN
        -- Hủy dịch vụ → hoàn trả kho
        IF :OLD.status IN ('PENDING', 'SCHEDULED', 'IN_PROGRESS', 'DONE')
        AND :NEW.status = 'CANCELLED' THEN
            sp_refund_service_stock(:NEW.booking_id, :NEW.service_id, :NEW.pet_id);
        END IF;

        -- Khôi phục dịch vụ từ CANCELLED → trừ kho lại
        IF :OLD.status = 'CANCELLED'
        AND :NEW.status IN ('PENDING', 'SCHEDULED', 'IN_PROGRESS', 'DONE') THEN
            sp_validate_and_execute_stock(:NEW.booking_id, :NEW.service_id, :NEW.pet_id);
        END IF;
    END IF;
END trg_bks_inventory_sync;
/

-- TRG-11: [MỚI - TRG-14] Tự động chuyển phòng sang IN_USE khi xếp thú cưng vào chuồng
-- Bảng: booking_room_pet
-- Thời điểm: AFTER INSERT
-- Mục đích:
--   Khi một thú cưng được xếp vào phòng (INSERT vào booking_room_pet),
--   hệ thống tự động cập nhật trạng thái phòng → IN_USE.
--   Đây là thời điểm thực tế phòng bắt đầu được sử dụng, khác với
--   thời điểm gán booking_room (có thể chỉ là đặt trước chưa check-in).
--   Đảm bảo không phòng nào có thú cưng mà vẫn bị đánh dấu AVAILABLE.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_auto_update_room_in_use
AFTER INSERT ON booking_room_pet
FOR EACH ROW
DECLARE
    v_room_id room.room_id%TYPE;
BEGIN
    -- Lấy room_id thông qua booking_room_id
    SELECT br.room_id
    INTO   v_room_id
    FROM   booking_room br
    WHERE  br.booking_room_id = :NEW.booking_room_id;

    -- Cập nhật trạng thái phòng sang IN_USE
    UPDATE room r
    SET    r.status = 'IN_USE'
    WHERE  r.room_id = v_room_id
      AND  r.status  <> 'MAINTENANCE';   -- Không ghi đè phòng đang bảo trì

EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;
END trg_auto_update_room_in_use;
/

-- TRG-12: [MỚI - TRG-15] Tự động trả phòng về AVAILABLE khi booking kết thúc
-- Bảng: booking
-- Thời điểm: AFTER UPDATE OF status
-- Mục đích:
--   Khi trạng thái booking chuyển sang CHECKED_OUT hoặc CANCELLED,
--   tất cả phòng liên kết qua booking_room được trả về AVAILABLE.
--   Chỉ cập nhật phòng đang IN_USE, không ảnh hưởng phòng MAINTENANCE.
--   Trigger kích hoạt sau UPDATE để đảm bảo giá trị :NEW.status đã được ghi.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_auto_update_room_available
AFTER UPDATE OF status ON booking
FOR EACH ROW
BEGIN
    IF :NEW.status IN ('CHECKED_OUT', 'CANCELLED')
    AND :OLD.status NOT IN ('CHECKED_OUT', 'CANCELLED') THEN
        UPDATE room r
        SET    r.status = 'AVAILABLE'
        WHERE  r.room_id IN (
            SELECT br.room_id
            FROM   booking_room br
            WHERE  br.booking_id = :NEW.booking_id
        )
        AND r.status = 'IN_USE';   -- Chỉ trả phòng đang được sử dụng
    END IF;
END trg_auto_update_room_available;
/

-- =========================================================
-- ── NHÓM 4: ĐỒNG BỘ TÀI CHÍNH ────────────────────────────────────────
-- =========================================================

-- TRG-13: Tự động cập nhật tổng tiền hóa đơn khi order_details thay đổi
-- Bảng: order_details
-- Thời điểm: AFTER INSERT OR UPDATE OR DELETE
-- Mục đích:
--   Duy trì sự toàn vẹn tài chính: mọi thay đổi trên chi tiết đơn hàng
--   (thêm / sửa / xóa dòng) đều phản ánh ngay vào subtotal và grand_total
--   của bảng orders. Xử lý cả trường hợp dòng bị chuyển sang order khác.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_sync_order_totals
AFTER INSERT OR UPDATE OR DELETE ON order_details
FOR EACH ROW
BEGIN
    IF INSERTING THEN
        UPDATE orders
        SET    subtotal    = subtotal + :NEW.line_total,
               grand_total = grand_total + :NEW.line_total
        WHERE  order_id = :NEW.order_id;

    ELSIF DELETING THEN
        UPDATE orders
        SET    subtotal    = subtotal - :OLD.line_total,
               grand_total = grand_total - :OLD.line_total
        WHERE  order_id = :OLD.order_id;

    ELSIF UPDATING THEN
        -- Dòng bị chuyển sang order khác
        IF :OLD.order_id <> :NEW.order_id THEN
            UPDATE orders
            SET    subtotal    = subtotal - :OLD.line_total,
                   grand_total = grand_total - :OLD.line_total
            WHERE  order_id = :OLD.order_id;

            UPDATE orders
            SET    subtotal    = subtotal + :NEW.line_total,
                   grand_total = grand_total + :NEW.line_total
            WHERE  order_id = :NEW.order_id;

        -- Cập nhật số lượng / đơn giá trên cùng order
        ELSE
            UPDATE orders
            SET    subtotal    = subtotal    + (:NEW.line_total - :OLD.line_total),
                   grand_total = grand_total + (:NEW.line_total - :OLD.line_total)
            WHERE  order_id = :NEW.order_id;
        END IF;
    END IF;
END trg_sync_order_totals;
/

-- TRG-14: [MỚI - TRG-16] Tự động khấu trừ tiền cọc khi tạo đơn hàng
-- Bảng: orders
-- Thời điểm: AFTER INSERT
-- Mục đích:
--   Khi một đơn hàng (Order) mới được tạo cho một booking có tiền cọc,
--   tự động tính lại grand_total = GREATEST(subtotal - deposit_amount, 0).
--   Đồng thời ghi nhận khoản cọc như một giao dịch thanh toán ảo vào
--   bảng payments với payment_method = 'DEPOSIT' và status = 'SUCCESS',
--   giúp PROC-05 (update_orders_status) đối soát chính xác.
--   Bỏ qua nếu booking không có cọc (deposit_amount = 0 hoặc NULL).
-- =========================================================
CREATE OR REPLACE TRIGGER trg_apply_deposit_on_order
AFTER INSERT ON orders
FOR EACH ROW
DECLARE
    v_deposit    booking.deposit_amount%TYPE;
    v_new_grand  orders.grand_total%TYPE;
    v_pay_id     VARCHAR2(10);
BEGIN
    -- Lấy tiền cọc từ booking tương ứng
    SELECT NVL(b.deposit_amount, 0)
    INTO   v_deposit
    FROM   booking b
    WHERE  b.booking_id = :NEW.booking_id;

    -- Chỉ xử lý khi có tiền cọc thực sự
    IF v_deposit > 0 THEN

        -- Tính grand_total sau khi khấu trừ cọc (không để âm)
        v_new_grand := GREATEST(:NEW.subtotal - v_deposit, 0);

        -- Cập nhật grand_total của đơn hàng vừa tạo
        UPDATE orders
        SET    grand_total = v_new_grand
        WHERE  order_id    = :NEW.order_id;

        -- Tự động sinh payment_id dạng 'DEP-XXXXX'
        v_pay_id := 'DEP-' || LPAD(:NEW.order_id, 5, '0');

        -- Ghi nhận khoản cọc như một thanh toán thành công
        INSERT INTO payments (
            payment_id, order_id, payment_method, provider,
            amount, status, paid_at, note
        ) VALUES (
            v_pay_id,
            :NEW.order_id,
            'DEPOSIT',                          -- Phân biệt với CASH / CARD / EWALLET
            'System',
            v_deposit,
            'SUCCESS',
            SYSTIMESTAMP,
            'Tiền cọc tự động khấu trừ từ booking ' || :NEW.booking_id
        );

    END IF;

EXCEPTION
    WHEN NO_DATA_FOUND THEN NULL;   -- Booking không có cọc → bỏ qua
    WHEN DUP_VAL_ON_INDEX THEN NULL;  -- Payment đã tồn tại (trường hợp tái tạo) → bỏ qua
END trg_apply_deposit_on_order;
/

-- TRG-15: Tự động đồng bộ trạng thái hóa đơn sau mỗi giao dịch thanh toán
-- Bảng: payments
-- Thời điểm: AFTER INSERT OR UPDATE (Compound Trigger)
-- Mục đích:
--   Sau khi có thanh toán mới hoặc thanh toán được cập nhật, gọi PROC-05
--   (update_orders_status) để đối soát tổng tiền đã nhận so với grand_total,
--   từ đó tự động chuyển trạng thái đơn hàng sang PAID hoặc PARTIAL.
--   Dùng Compound Trigger để tránh lỗi mutating table.
--   Xử lý thêm trường hợp order_id bị đổi (cập nhật order cũ trước).
-- =========================================================
CREATE OR REPLACE TRIGGER trg_payment_logic_sync
FOR INSERT OR UPDATE ON payments
COMPOUND TRIGGER

AFTER EACH ROW IS
BEGIN
    -- Nếu order_id bị thay đổi, cập nhật trạng thái order cũ trước
    IF UPDATING AND :OLD.order_id <> :NEW.order_id THEN
        update_orders_status(:OLD.order_id);
    END IF;

    -- Luôn cập nhật trạng thái order hiện tại
    update_orders_status(:NEW.order_id);
END AFTER EACH ROW;

END trg_payment_logic_sync;
/

-- TRG-16: Chặn chốt hóa đơn thủ công khi chưa đủ điều kiện
-- Bảng: orders
-- Thời điểm: BEFORE UPDATE OF status
-- Mục đích:
--   Phòng ngừa nhân viên lễ tân vô tình chốt hóa đơn (PAID) khi:
--   (1) Vẫn còn dịch vụ đang được thực hiện (chưa DONE/CANCELLED).
--   (2) Khách hàng chưa checkout phòng (booking chưa CHECKED_OUT/CANCELLED).
--   (3) Tổng tiền đã thu chưa đủ so với grand_total.
--   Dùng fn_is_order_ready_to_pay và fn_get_total_paid để kiểm tra.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_prevent_manual_paid_status
BEFORE UPDATE OF status ON orders
FOR EACH ROW
DECLARE
    v_total_paid orders.grand_total%TYPE;
BEGIN
    IF :NEW.status = 'PAID' THEN

        -- Điều kiện 1: Dịch vụ và phòng phải hoàn tất
        IF NOT fn_is_order_ready_to_pay(:NEW.order_id) THEN
            RAISE_APPLICATION_ERROR(
                -20050,
                'LỖI THANH TOÁN: Không thể chốt hóa đơn — còn dịch vụ hoặc phòng chưa hoàn tất.'
            );
        END IF;

        -- Điều kiện 2: Tổng tiền đã thu phải đủ
        v_total_paid := fn_get_total_paid(:NEW.order_id);
        IF v_total_paid < :NEW.grand_total THEN
            RAISE_APPLICATION_ERROR(
                -20051,
                'LỖI THANH TOÁN: Tổng tiền đã thu (' || v_total_paid ||
                ') chưa đủ so với grand_total (' || :NEW.grand_total || ').'
            );
        END IF;

    END IF;
END trg_prevent_manual_paid_status;
/

-- =========================================================
-- ── NHÓM 5: AUDIT LOG ─────────────────────────────────────────────────
-- =========================================================

-- TRG-17: Ghi nhật ký kiểm toán khi trạng thái thanh toán thay đổi
-- Bảng: payments
-- Thời điểm: AFTER INSERT OR UPDATE
-- Mục đích:
--   Bất kỳ khi nào cột status của payments thay đổi (PENDING → SUCCESS,
--   SUCCESS → REFUNDED, v.v.), ghi một bản ghi đầy đủ vào payment_audit_log
--   để phục vụ đối soát tài chính, kiểm toán và truy vết lỗi sau này.
--   INSERT mới cũng được ghi log với old_status = NULL.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_payment_audit
AFTER INSERT OR UPDATE ON payments
FOR EACH ROW
DECLARE
    v_customer_id orders.customer_id%TYPE;
BEGIN
    -- Chỉ ghi log khi có thay đổi thực sự về trạng thái
    IF INSERTING OR (UPDATING AND :NEW.status <> :OLD.status) THEN

        -- Lấy customer_id từ orders
        BEGIN
            SELECT o.customer_id
            INTO   v_customer_id
            FROM   orders o
            WHERE  o.order_id = :NEW.order_id;
        EXCEPTION
            WHEN NO_DATA_FOUND THEN
                v_customer_id := NULL;
        END;

        INSERT INTO payment_audit_log (
            payment_id, order_id, customer_id,
            amount, payment_method, action,
            old_status, new_status
        ) VALUES (
            :NEW.payment_id,
            :NEW.order_id,
            v_customer_id,
            :NEW.amount,
            :NEW.payment_method,
            :NEW.status,
            CASE WHEN INSERTING THEN NULL ELSE :OLD.status END,
            :NEW.status
        );

    END IF;
END trg_payment_audit;
/

-- TRG-18: Ghi cảnh báo khi tồn kho chạm ngưỡng đặt hàng lại
-- Bảng: branch_inventory
-- Thời điểm: AFTER UPDATE OF quantity_in_stock
-- Mục đích:
--   Khi quantity_in_stock giảm xuống bằng hoặc dưới reorder_point
--   (và trước đó còn trên ngưỡng), ghi một bản ghi vào stock_alert_log.
--   Giúp bộ phận mua hàng phát hiện kịp thời và tái nhập kho.
--   Không raise lỗi để tránh gián đoạn luồng nghiệp vụ chính.
-- =========================================================
CREATE OR REPLACE TRIGGER trg_stock_reorder_alert
AFTER UPDATE OF quantity_in_stock ON branch_inventory
FOR EACH ROW
BEGIN
    -- Chỉ ghi khi tồn kho VỪA chạm / vượt xuống dưới ngưỡng reorder
    IF :NEW.reorder_point IS NOT NULL
    AND :NEW.quantity_in_stock  <= :NEW.reorder_point
    AND :OLD.quantity_in_stock  >  :OLD.reorder_point THEN

        INSERT INTO stock_alert_log (branch_id, product_id, quantity, reorder_point)
        VALUES (:NEW.branch_id, :NEW.product_id, :NEW.quantity_in_stock, :NEW.reorder_point);

    END IF;
END trg_stock_reorder_alert;
/

-- =========================================================
-- TRG-19: Tự động cộng tồn kho khi phiếu nhập hàng được duyệt
-- Bảng: goods_receipt
-- Thời điểm: AFTER UPDATE OF status
-- =========================================================
CREATE OR REPLACE TRIGGER trg_approve_goods_receipt
AFTER UPDATE OF status ON goods_receipt
FOR EACH ROW
DECLARE
    v_qty_conv   NUMBER;
    v_product_id VARCHAR2(10); -- [ĐÃ THÊM] Khai báo biến trung gian
BEGIN
    -- Chỉ thực thi khi trạng thái chuyển từ DRAFT sang APPROVED
    IF :NEW.status = 'APPROVED' AND :OLD.status = 'DRAFT' THEN
        
        -- Duyệt qua tất cả các mặt hàng trong phiếu nhập
        FOR rec IN (
            SELECT product_id, quantity, unit
            FROM   goods_receipt_detail
            WHERE  goods_receipt_id = :NEW.goods_receipt_id
        ) LOOP
            
            -- [ĐÃ SỬA] Gán giá trị Record vào biến trung gian
            v_product_id := rec.product_id;
            
            -- 1. Quy đổi định mức đơn vị nhập về định mức lưu kho (ML, G)
            v_qty_conv := fn_convert_unit(rec.quantity, rec.unit);

            -- 2. Cập nhật tồn kho (Cộng thêm nếu đã có, Thêm mới nếu chưa có)
            MERGE INTO branch_inventory bi
            USING DUAL ON (bi.branch_id = :NEW.branch_id AND bi.product_id = v_product_id)
            WHEN MATCHED THEN
                UPDATE SET bi.quantity_in_stock = bi.quantity_in_stock + v_qty_conv,
                           bi.last_updated      = SYSTIMESTAMP
            WHEN NOT MATCHED THEN
                INSERT (branch_id, product_id, quantity_in_stock, last_updated)
                VALUES (:NEW.branch_id, v_product_id, v_qty_conv, SYSTIMESTAMP);
                
        END LOOP;
    END IF;
END trg_approve_goods_receipt;
/

-- =========================================================
-- TRG-20: Tự động đồng bộ tồn kho thực tế khi hoàn tất kiểm kho
-- Bảng: stock_audit
-- Thời điểm: AFTER UPDATE OF status
-- =========================================================
CREATE OR REPLACE TRIGGER trg_complete_stock_audit
AFTER UPDATE OF status ON stock_audit
FOR EACH ROW
BEGIN
    -- Chỉ thực thi khi trạng thái chuyển từ DRAFT sang COMPLETED
    IF :NEW.status = 'COMPLETED' AND :OLD.status = 'DRAFT' THEN
        
        -- Duyệt qua các mặt hàng có biên bản kiểm đếm
        FOR rec IN (
            SELECT product_id, actual_quantity
            FROM   stock_audit_detail
            WHERE  stock_audit_id = :NEW.stock_audit_id
        ) LOOP
            
            -- Ghi đè số lượng tồn kho trên hệ thống bằng số lượng thực tế
            UPDATE branch_inventory bi
            SET    bi.quantity_in_stock = rec.actual_quantity,
                   bi.last_updated      = SYSTIMESTAMP
            WHERE  bi.branch_id  = :NEW.branch_id
              AND  bi.product_id = rec.product_id;
              
        END LOOP;
    END IF;
END trg_complete_stock_audit;
/

-- =========================================================
-- TRG-21: Tự động tính toán thành tiền cho chi tiết hóa đơn
-- Bảng: order_details
-- Thời điểm: BEFORE INSERT OR UPDATE
-- Mục đích:
--   Ngăn chặn tầng Application truyền sai line_total.
--   Đảm bảo công thức Thành tiền = Số lượng * Đơn giá luôn đúng
--   cho cả Tiền phòng (quantity = số ngày) và Tiền dịch vụ (quantity = số lần).
-- =========================================================
CREATE OR REPLACE TRIGGER trg_calc_order_line_total
BEFORE INSERT OR UPDATE ON order_details
FOR EACH ROW
BEGIN
    -- Ép buộc tính toán lại line_total trước khi ghi vào database
    -- Bỏ qua thao tác này nếu số lượng hoặc đơn giá bị NULL (mặc dù đã có constraint NOT NULL)
    IF :NEW.quantity IS NOT NULL AND :NEW.unit_price IS NOT NULL THEN
        :NEW.line_total := :NEW.quantity * :NEW.unit_price;
    END IF;
END trg_calc_order_line_total;
/
