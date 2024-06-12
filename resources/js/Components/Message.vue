<template>
    <div v-if="show" :aria-live="ariaLive" :class="getClass" role="alert">
        {{ message + errorMessage }}
    </div>
</template>

<script>
export default {
    props: {
        message: String,
        success: Boolean,
        hide: {
            type: Boolean,
            default: false
        },
        errors: {
            default: () => []
        }
    },
    data() {
        return {
            show: !this.hide,
        };
    },
    computed: {
        getClass() {
            return {
                'text-success': this.success,
                'text-danger': !this.success
            };
        },
        errorMessage() {
            let message = '';
            for (let error in this.errors) {
                message = message + ' ' + error + ' => ' + this.errors[error];
            }
            return message;
        },
        ariaLive() {
            // Determine the appropriate value for aria-live based on the urgency of the message
            return this.success ? 'polite' : 'assertive';
        }
    },
    methods: {
        hideShowMessage(show, time = 2500) {
            setTimeout(() => {
                this.show = show;
                if (show) {
                    this.$el.focus(); // Focus the message when it appears
                }
            }, time);
        },
        hideMessage() {
            this.hideShowMessage(false);
        },
        showMessage() {
            this.hideShowMessage(true, 500);
        }
    },
    watch: {
        message(newValue) {
            if (this.hide && newValue) {
                this.showMessage();
                // this.hideMessage();
            }
        }
    },
    mounted() {
        if (this.show) {
            this.$el.focus(); // Ensure the message is focused when it's initially rendered
        }
    },
    updated() {
        if (this.show) {
            this.$el.focus(); // Ensure the message retains focus after updates
        }
    }
};
</script>
