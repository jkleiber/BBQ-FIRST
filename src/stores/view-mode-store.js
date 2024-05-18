import { defineStore } from "pinia";

export const useViewModeStore = defineStore('viewMode', {
    state() {
        return {
            screenWidth: window.innerWidth
        }
    },
    getters: {
        isMobile() {
            return this.screenWidth <= 720;
        }
    },
    actions: {
        updateScreenWidth(width) {
            this.screenWidth = width;
        }
    }
});
