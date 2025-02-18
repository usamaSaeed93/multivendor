<template>
    <div class="megamenu-container">
        <button class="menu-button" @mouseover="showMegaMenu" @mouseleave="hideMegaMenu">Categories</button>
        <div v-if="isMenuOpen" class="megamenu-wrapper" @mouseover="cancelHide" @mouseleave="hideMegaMenu">
            <div class="main-categories">
                <div v-for="index in 8" :key="index" @mouseover="changeMainCategory(index)"
                    :class="['main-category', isActive(index) ? 'active' : '']">
                    <div class="icon"><img src="../assets/main-category.svg" /></div>
                    <div class="label">Main category {{ index }}</div>
                </div>
            </div>
            <div class="sub-categories">
                <div v-for="index in 15" :key="index" class="sub-category">
                    <div class="icon"><img src="../assets/sub-category.svg" /></div>
                    <div class="label">
                        Sub-category {{ activeMainCategory }}/{{ index }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const activeMainCategory = ref(1);
const isMenuOpen = ref(false);
const hideTimeout = ref(null);

const isActive = (key) => activeMainCategory.value === key;

const changeMainCategory = (index) => {
    activeMainCategory.value = index;
};

const showMegaMenu = () => {
    clearTimeout(hideTimeout.value);
    isMenuOpen.value = true;
};

const hideMegaMenu = () => {
    hideTimeout.value = setTimeout(() => {
        isMenuOpen.value = false;
    }, 300);
};

const cancelHide = () => {
    clearTimeout(hideTimeout.value);
};
</script>

<style scoped>
.megamenu-container {
    /* position: relative; */
    /* width: 100%; */
}

.menu-button {
    background: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

.megamenu-wrapper {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100vw;
    height: 400px;
    border: 1px solid #a4a4a4;
    border-top: none;
    background: #f8f8f8;
    display: grid;
    grid-template-columns: 1fr 3fr;
    gap: 0px;
    z-index: 1000;
}

.main-categories {
    display: grid;
    grid-template-columns: 1fr;
    border-right: 1px solid #a4a4a4;
}

.main-category {
    display: flex;
    cursor: pointer;
    flex-direction: row;
    align-items: center;
    padding-left: 30px;
}

.main-category:not(:last-of-type) {
    border-bottom: 1px solid #a4a4a4;
}

.main-category .icon {
    margin-right: 15px;
}

.main-category.active {
    background: #efefef;
}

.sub-categories {
    background: #efefef;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
    padding: 20px;
}

.sub-category {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.sub-category:hover {
    background: #dedede;
}

.sub-category .label {
    margin-top: 15px;
}

@media (max-width: 768px) {
    .megamenu-wrapper {
        grid-template-columns: 1fr;
    }

    .sub-categories {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    }
}
</style>