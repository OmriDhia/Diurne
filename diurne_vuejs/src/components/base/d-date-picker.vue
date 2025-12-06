<template>
  <div class="form-group">
    <label v-if="label" class="text-black">{{ label }}</label>
    <div class="input-group">
      <input
        type="text"
        class="form-control"
        :value="formattedDate"
        @click="showPicker = !showPicker"
        readonly
        :placeholder="placeholder"
      />
      <span class="input-group-text">
        <vue-feather type="calendar" size="16"></vue-feather>
      </span>
    </div>
    
    <div class="datepicker-dropdown" v-if="showPicker" v-click-outside="hidePicker">
      <div class="datepicker-header">
        <button type="button" class="btn btn-icon" @click="previousMonth">
          <vue-feather type="chevron-left" size="16"></vue-feather>
        </button>
        <span class="datepicker-month-year">
          {{ months[month] }} {{ year }}
        </span>
        <button type="button" class="btn btn-icon" @click="nextMonth">
          <vue-feather type="chevron-right" size="16"></vue-feather>
        </button>
      </div>
      
      <div class="datepicker-body">
        <div class="datepicker-weekdays">
          <div v-for="day in weekdays" :key="day" class="weekday">
            {{ day }}
          </div>
        </div>
        
        <div class="datepicker-days">
          <div
            v-for="(date, index) in calendarDays"
            :key="index"
            class="day"
            :class="{
              'other-month': !date.currentMonth,
              'today': date.isToday,
              'selected': date.isSelected,
              'disabled': date.isDisabled
            }"
            @click="selectDate(date)"
          >
            {{ date.day }}
          </div>
        </div>
      </div>
      
      <div class="datepicker-footer" v-if="showTodayButton">
        <button type="button" class="btn btn-sm btn-outline-secondary" @click="selectToday">
          Aujourd'hui
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch, onMounted } from 'vue';
import VueFeather from 'vue-feather';
import { format, parseISO, isToday, isSameMonth, isSameDay, addMonths, subMonths, getDaysInMonth, startOfMonth, getDay, addDays } from 'date-fns';
import { fr } from 'date-fns/locale';

export default {
  name: 'DDatePicker',
  components: {
    VueFeather
  },
  props: {
    label: {
      type: String,
      default: ''
    },
    modelValue: {
      type: [String, Date],
      default: null
    },
    placeholder: {
      type: String,
      default: 'Sélectionnez une date'
    },
    formatString: {
      type: String,
      default: 'dd/MM/yyyy'
    },
    minDate: {
      type: [String, Date],
      default: null
    },
    maxDate: {
      type: [String, Date],
      default: null
    },
    showTodayButton: {
      type: Boolean,
      default: true
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const showPicker = ref(false);
    const today = new Date();
    const selectedDate = ref(null);
    const currentMonth = ref(today.getMonth());
    const currentYear = ref(today.getFullYear());
    
    const months = [
      'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
      'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];
    
    const weekdays = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
    
    // Initialize with props
    onMounted(() => {
      if (props.modelValue) {
        const date = props.modelValue instanceof Date ? props.modelValue : parseISO(props.modelValue);
        selectedDate.value = date;
        currentMonth.value = date.getMonth();
        currentYear.value = date.getFullYear();
      }
    });
    
    // Watch for external modelValue changes
    watch(() => props.modelValue, (newVal) => {
      if (newVal) {
        const date = newVal instanceof Date ? newVal : parseISO(newVal);
        selectedDate.value = date;
        currentMonth.value = date.getMonth();
        currentYear.value = date.getFullYear();
      } else {
        selectedDate.value = null;
      }
    });
    
    const formattedDate = computed(() => {
      if (!selectedDate.value) return '';
      return format(selectedDate.value, props.formatString, { locale: fr });
    });
    
    const calendarDays = computed(() => {
      const days = [];
      const firstDayOfMonth = startOfMonth(new Date(currentYear.value, currentMonth.value, 1));
      const daysInMonth = getDaysInMonth(firstDayOfMonth);
      const startWeekday = getDay(firstDayOfMonth) === 0 ? 6 : getDay(firstDayOfMonth) - 1; // Adjust for Monday start
      
      // Previous month days
      const prevMonthDays = startWeekday;
      const prevMonth = subMonths(firstDayOfMonth, 1);
      const prevMonthDaysCount = getDaysInMonth(prevMonth);
      
      for (let i = prevMonthDaysCount - prevMonthDays + 1; i <= prevMonthDaysCount; i++) {
        const date = new Date(prevMonth.getFullYear(), prevMonth.getMonth(), i);
        days.push({
          day: i,
          date,
          currentMonth: false,
          isToday: isToday(date),
          isSelected: selectedDate.value ? isSameDay(date, selectedDate.value) : false,
          isDisabled: isDateDisabled(date)
        });
      }
      
      // Current month days
      for (let i = 1; i <= daysInMonth; i++) {
        const date = new Date(currentYear.value, currentMonth.value, i);
        days.push({
          day: i,
          date,
          currentMonth: true,
          isToday: isToday(date),
          isSelected: selectedDate.value ? isSameDay(date, selectedDate.value) : false,
          isDisabled: isDateDisabled(date)
        });
      }
      
      // Next month days
      const daysToAdd = 42 - days.length; // 6 weeks
      for (let i = 1; i <= daysToAdd; i++) {
        const date = new Date(currentYear.value, currentMonth.value + 1, i);
        days.push({
          day: i,
          date,
          currentMonth: false,
          isToday: isToday(date),
          isSelected: selectedDate.value ? isSameDay(date, selectedDate.value) : false,
          isDisabled: isDateDisabled(date)
        });
      }
      
      return days;
    });
    
    const month = computed(() => currentMonth.value);
    const year = computed(() => currentYear.value);
    
    function isDateDisabled(date) {
      if (props.disabled) return true;
      
      if (props.minDate) {
        const minDate = props.minDate instanceof Date ? props.minDate : parseISO(props.minDate);
        if (date < minDate) return true;
      }
      
      if (props.maxDate) {
        const maxDate = props.maxDate instanceof Date ? props.maxDate : parseISO(props.maxDate);
        if (date > maxDate) return true;
      }
      
      return false;
    }
    
    function selectDate(date) {
      if (date.isDisabled) return;
      
      selectedDate.value = date.date;
      emit('update:modelValue', date.date);
      showPicker.value = false;
    }
    
    function selectToday() {
      const today = new Date();
      if (!isDateDisabled(today)) {
        selectedDate.value = today;
        currentMonth.value = today.getMonth();
        currentYear.value = today.getFullYear();
        emit('update:modelValue', today);
        showPicker.value = false;
      }
    }
    
    function previousMonth() {
      const newDate = subMonths(new Date(currentYear.value, currentMonth.value, 1), 1);
      currentMonth.value = newDate.getMonth();
      currentYear.value = newDate.getFullYear();
    }
    
    function nextMonth() {
      const newDate = addMonths(new Date(currentYear.value, currentMonth.value, 1), 1);
      currentMonth.value = newDate.getMonth();
      currentYear.value = newDate.getFullYear();
    }
    
    function hidePicker() {
      showPicker.value = false;
    }
    
    return {
      showPicker,
      months,
      weekdays,
      month,
      year,
      calendarDays,
      formattedDate,
      selectDate,
      selectToday,
      previousMonth,
      nextMonth,
      hidePicker
    };
  },
  directives: {
    'click-outside': {
      beforeMount(el, binding) {
        el.clickOutsideEvent = function(event) {
          if (!(el === event.target || el.contains(event.target))) {
            binding.value();
          }
        };
        document.addEventListener('click', el.clickOutsideEvent);
      },
      unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
      }
    }
  }
};
</script>

<style scoped>
.form-group {
  position: relative;
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.input-group {
  position: relative;
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
  width: 100%;
}

.form-control {
  display: block;
  width: 100%;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  cursor: pointer;
}

.input-group-text {
  display: flex;
  align-items: center;
  padding: 0.375rem 0.75rem;
  margin-bottom: 0;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  text-align: center;
  white-space: nowrap;
  background-color: #e9ecef;
  border: 1px solid #ced4da;
  border-left: none;
  border-radius: 0 0.25rem 0.25rem 0;
  cursor: pointer;
}

.datepicker-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: block;
  min-width: 270px;
  padding: 0.5rem;
  margin: 0.125rem 0 0;
  font-size: 1rem;
  color: #212529;
  text-align: center;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 0.25rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
}

.datepicker-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  border-bottom: 1px solid #dee2e6;
}

.datepicker-month-year {
  font-weight: 500;
}

.btn-icon {
  padding: 0.25rem;
  background: transparent;
  border: none;
  cursor: pointer;
}

.datepicker-body {
  padding: 0.5rem;
}

.datepicker-weekdays {
  display: flex;
  margin-bottom: 0.5rem;
}

.weekday {
  flex: 1;
  font-weight: 500;
  font-size: 0.875rem;
  color: #6c757d;
}

.datepicker-days {
  display: flex;
  flex-wrap: wrap;
}

.day {
  flex: 0 0 calc(100% / 7);
  padding: 0.25rem;
  border-radius: 0.25rem;
  cursor: pointer;
}

.day:hover:not(.disabled) {
  background-color: #f8f9fa;
}

.other-month {
  color: #adb5bd;
}

.today {
  font-weight: bold;
  color: #0d6efd;
}

.selected {
  background-color: #0d6efd;
  color: white;
}

.selected:hover {
  background-color: #0b5ed7;
}

.disabled {
  color: #adb5bd;
  cursor: not-allowed;
  opacity: 0.5;
}

.datepicker-footer {
  padding: 0.5rem;
  border-top: 1px solid #dee2e6;
  text-align: center;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 0.2rem;
}

.btn-outline-secondary {
  color: #6c757d;
  border-color: #6c757d;
}

.btn-outline-secondary:hover {
  color: #fff;
  background-color: #6c757d;
  border-color: #6c757d;
}
</style>