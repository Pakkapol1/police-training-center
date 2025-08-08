/**
 * Thai Date Picker - แสดงปี พ.ศ. แทนปี ค.ศ.
 * ใช้งาน: <input type="text" class="thai-datepicker form-control">
 */

class ThaiDatePicker {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            format: 'dd/mm/yyyy',
            placeholder: 'วว/ดด/พศศศ',
            ...options
        };
        this.init();
    }

    init() {
        // สร้าง wrapper
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'thai-datepicker-wrapper position-relative';
        this.element.parentNode.insertBefore(this.wrapper, this.element);
        this.wrapper.appendChild(this.element);

        // สร้าง hidden date input สำหรับ browser date picker
        this.hiddenInput = document.createElement('input');
        this.hiddenInput.type = 'date';
        this.hiddenInput.className = 'position-absolute';
        this.hiddenInput.style.cssText = 'opacity: 0; pointer-events: none; top: 0; left: 0; width: 100%; height: 100%;';
        this.wrapper.appendChild(this.hiddenInput);

        // ตั้งค่า element หลัก
        this.element.setAttribute('readonly', 'readonly');
        this.element.style.cursor = 'pointer';
        this.element.placeholder = this.options.placeholder;

        // เพิ่ม event listeners
        this.bindEvents();

        // ถ้ามีค่าเริ่มต้น ให้แปลงและแสดงผล
        if (this.element.value) {
            this.setDateFromISO(this.element.value);
        }
    }

    bindEvents() {
        // คลิกที่ text input เพื่อเปิด date picker
        this.element.addEventListener('click', () => {
            this.hiddenInput.showPicker();
        });

        // เมื่อมีการเปลี่ยนแปลงวันที่
        this.hiddenInput.addEventListener('change', () => {
            const isoDate = this.hiddenInput.value;
            if (isoDate) {
                const thaiDate = this.formatToThai(isoDate);
                this.element.value = thaiDate;
                this.element.setAttribute('data-iso-date', isoDate);
                
                // Trigger change event on original element
                this.element.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        // Focus events
        this.element.addEventListener('focus', () => {
            this.wrapper.classList.add('focused');
        });

        this.element.addEventListener('blur', () => {
            this.wrapper.classList.remove('focused');
        });
    }

    // แปลงจาก ISO date (YYYY-MM-DD) เป็นรูปแบบไทย (DD/MM/YYYY+543)
    formatToThai(isoDate) {
        if (!isoDate) return '';
        
        const date = new Date(isoDate + 'T00:00:00');
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear() + 543;
        
        return `${day}/${month}/${year}`;
    }

    // แปลงจากรูปแบบไทย (DD/MM/YYYY+543) เป็น ISO date (YYYY-MM-DD)
    parseFromThai(thaiDate) {
        if (!thaiDate) return '';
        
        const parts = thaiDate.split('/');
        if (parts.length !== 3) return '';
        
        const day = parseInt(parts[0]);
        const month = parseInt(parts[1]);
        const year = parseInt(parts[2]) - 543;
        
        if (day < 1 || day > 31 || month < 1 || month > 12 || year < 1900) {
            return '';
        }
        
        return `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
    }

    // ตั้งค่าวันที่จาก ISO format
    setDateFromISO(isoDate) {
        if (isoDate) {
            this.hiddenInput.value = isoDate;
            this.element.value = this.formatToThai(isoDate);
            this.element.setAttribute('data-iso-date', isoDate);
        }
    }

    // ดึงค่าวันที่ในรูปแบบ ISO
    getISODate() {
        return this.element.getAttribute('data-iso-date') || '';
    }

    // ตั้งค่าวันที่จากรูปแบบไทย
    setThaiDate(thaiDate) {
        const isoDate = this.parseFromThai(thaiDate);
        if (isoDate) {
            this.setDateFromISO(isoDate);
        }
    }

    // ล้างค่า
    clear() {
        this.element.value = '';
        this.hiddenInput.value = '';
        this.element.removeAttribute('data-iso-date');
    }

    // ตรวจสอบว่ามีค่าหรือไม่
    hasValue() {
        return this.getISODate() !== '';
    }

    // Validation - ตรวจสอบว่าวันที่ถูกต้องหรือไม่
    isValid() {
        const isoDate = this.getISODate();
        if (!isoDate) return true; // ถ้าไม่มีค่าถือว่า valid
        
        const date = new Date(isoDate + 'T00:00:00');
        return !isNaN(date.getTime());
    }
}

// Auto-initialize เมื่อ DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initThaiDatePickers();
});

// ฟังก์ชันสำหรับ initialize date pickers
function initThaiDatePickers() {
    document.querySelectorAll('.thai-datepicker').forEach(element => {
        if (!element.thaiDatePicker) {
            element.thaiDatePicker = new ThaiDatePicker(element);
        }
    });
}

// Helper functions
window.ThaiDatePicker = ThaiDatePicker;
window.initThaiDatePickers = initThaiDatePickers;

// ฟังก์ชันช่วยเหลือสำหรับการ validation
window.validateThaiDateRange = function(startElement, endElement) {
    if (!startElement.thaiDatePicker || !endElement.thaiDatePicker) {
        return true;
    }
    
    const startDate = startElement.thaiDatePicker.getISODate();
    const endDate = endElement.thaiDatePicker.getISODate();
    
    if (!startDate || !endDate) {
        return true; // ถ้าไม่มีวันที่ใดวันที่หนึ่ง ถือว่า valid
    }
    
    return new Date(startDate) <= new Date(endDate);
};

// CSS สำหรับ Thai Date Picker
const style = document.createElement('style');
style.textContent = `
.thai-datepicker-wrapper {
    position: relative;
}

.thai-datepicker-wrapper.focused .thai-datepicker {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.thai-datepicker {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='%23212529' d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zM1 5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5H1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 16px;
    padding-right: 2.5rem;
}

.thai-datepicker.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4 1.4-1.4M6 8V4'/%3e%3c/svg%3e");
}

.thai-datepicker.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
`;
document.head.appendChild(style); 