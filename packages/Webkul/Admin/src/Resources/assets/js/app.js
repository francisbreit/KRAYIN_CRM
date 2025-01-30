/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);

/**
 * Main vue bundler.
 */
import { createApp } from "vue/dist/vue.esm-bundler";
import flatpickr from 'flatpickr'; // Importação do Flatpickr

/**
 * Main root application registry.
 */
window.app = createApp({
    data() {
        return {
            isMenuActive: false,
            hoveringMenu: '',
        };
    },

    created() {
        window.addEventListener('click', this.handleFocusOut);

        // Configurar fallback manual para tradução no Flatpickr
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr(".date-input", {
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                        longhand: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado']
                    },
                    months: {
                        shorthand: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                        longhand: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']
                    }
                }
            });
        });
    },

    beforeDestroy() {
        window.removeEventListener('click', this.handleFocusOut);
    },

    methods: {
        onSubmit() {},

        onInvalidSubmit({ values, errors, results }) {
            setTimeout(() => {
                const errorKeys = Object.entries(errors)
                    .map(([key, value]) => ({ key, value }))
                    .filter(error => error["value"].length);

                let firstErrorElement = document.querySelector('[name="' + errorKeys[0]["key"] + '"]');

                firstErrorElement.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
            }, 100);
        },

        handleMouseOver(event) {
            if (this.isMenuActive) {
                return;
            }

            const parentElement = event.currentTarget.parentElement;

            if (parentElement.classList.contains('sidebar-collapsed')) {
                parentElement.classList.remove('sidebar-collapsed');
                parentElement.classList.add('sidebar-not-collapsed');
            }
        },

        handleMouseLeave(event) {
            if (this.isMenuActive) {
                return;
            }

            const parentElement = event.currentTarget.parentElement;

            if (parentElement.classList.contains('sidebar-not-collapsed')) {
                parentElement.classList.remove('sidebar-not-collapsed');
                parentElement.classList.add('sidebar-collapsed');
            }
        },

        handleFocusOut(event) {
            const sidebar = this.$refs.sidebar;

            if (sidebar && !sidebar.contains(event.target)) {
                this.isMenuActive = false;

                const parentElement = sidebar.parentElement;

                if (parentElement.classList.contains('sidebar-not-collapsed')) {
                    parentElement.classList.remove('sidebar-not-collapsed');
                    parentElement.classList.add('sidebar-collapsed');
                }
            }
        },
    },
});

/**
 * Global plugins registration.
 */
import Admin from "./plugins/admin";
import Axios from "./plugins/axios";
import Emitter from "./plugins/emitter";
import Flatpickr from "./plugins/flatpickr";
import VeeValidate from "./plugins/vee-validate";
import CreateElement from "./plugins/createElement";
import Draggable from "./plugins/draggable";
import VueCal from "./plugins/vue-cal";
[
    Admin,
    Axios,
    Emitter,
    CreateElement,
    Draggable,
    Flatpickr,
    VeeValidate,
    VueCal,
].forEach((plugin) => app.use(plugin));

/**
 * Global directives.
 */
import Debounce from "./directives/debounce";
import DOMPurify from "./directives/dompurify";

app.directive("debounce", Debounce);
app.directive("safe-html", DOMPurify);

export default app;
