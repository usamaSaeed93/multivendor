<template>
    <div class="megamenu-container">
        <button class="menu-button" @mouseover="showMegaMenu" @mouseleave="hideMegaMenu">Categories</button>
        <div v-if="isMenuOpen" class="megamenu-wrapper" @mouseover="cancelHide" @mouseleave="hideMegaMenu">
            <div class="main-categories">
                <div v-for="category in categories" :key="category.id" @mouseover="changeMainCategory(category)"
                    :class="['main-category', isActive(category) ? 'active' : '']">
                    <div class="icon">
                        <!-- <img :src="category.image || defaultMainCategoryIcon" alt="Main Category" /> -->
                    </div>
                    <div class="label">{{ category.name }}</div>
                </div>
            </div>
            <div class="sub-categories">
                <div v-for="subCategory in activeMainCategory?.sub_categories || []" :key="subCategory.id"
                    @click="$router.push({ name: 'customer.search', query: { categories: subCategory.id } })"
                    class="sub-category">

                    <div class="icon">
                        <img :src="getSubCategoryImage(subCategory)" alt="Sub Category" class="sub-category-image" />
                    </div>
                    <div class="label">{{ subCategory.name }}</div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Request from "@js/services/api/request";
import { customerAPI } from "@js/services/api/request_url";
import { handleException } from "@js/services/api/handle_exception";

const categories = ref([]);
const activeMainCategory = ref(null);
const isMenuOpen = ref(false);
const hideTimeout = ref(null);

const defaultMainCategoryIcon = "../assets/main-category.svg";
const defaultSubCategoryIcon = "../assets/sub-category.svg";
const subCategoryImages = ref({}); // Stores dynamically fetched images

const UNSPLASH_ACCESS_KEY = "1rzDrSBufsvjo1-ALJnn7t3RkT1RKS2j7Dr6hNFxkZc"; // Replace with your API key

// Fetch categories from API
const fetchCategories = async () => {
    try {
        const response = await Request.getAuth(customerAPI.categories.get);
        categories.value = response.data.data;
        if (categories.value.length > 0) {
            activeMainCategory.value = categories.value[0];
        }
        await fetchSubCategoryImages();
    } catch (error) {
        handleException(error);
    }
};

// Fetch images for subcategories using Unsplash
const fetchSubCategoryImages = async () => {
    for (const category of categories.value) {
        for (const subCategory of category.sub_categories || []) {
            if (!subCategory.image && !subCategoryImages.value[subCategory.name]) {
                subCategoryImages.value[subCategory.name] = await fetchImage(subCategory.name);
            }
        }
    }
};

// Function to get an image from Unsplash
const fetchImage = async (query) => {
    try {
        const response = await axios.get(`https://api.unsplash.com/search/photos`, {
            params: {
                query: query,
                client_id: UNSPLASH_ACCESS_KEY,
                per_page: 1
            }
        });

        return response.data.results.length > 0 ? response.data.results[0].urls.small : defaultSubCategoryIcon;
    } catch (error) {
        console.error("Error fetching image:", error);
        return defaultSubCategoryIcon;
    }
};

// Returns the subcategory image (either from API or fetched from Unsplash)
const getSubCategoryImage = (subCategory) => {
    return subCategory.image || subCategoryImages.value[subCategory.name] || defaultSubCategoryIcon;
};

const isActive = (category) => activeMainCategory.value?.id === category.id;
const changeMainCategory = (category) => { activeMainCategory.value = category; };
const showMegaMenu = () => { clearTimeout(hideTimeout.value); isMenuOpen.value = true; };
const hideMegaMenu = () => { hideTimeout.value = setTimeout(() => { isMenuOpen.value = false; }, 300); };
const cancelHide = () => { clearTimeout(hideTimeout.value); };

onMounted(() => { fetchCategories(); });
</script>

<style scoped>
.megamenu-container {}

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
    width: 30px;
    height: 30px;
}

.main-category.active {
    background: #efefef;
}

.sub-categories {
    /* display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); */
    display: flex;
    flex-wrap: wrap;

    gap: 10px;
    padding: 20px;
    overflow-y: auto;
    overflow-x: hidden;
    /* Prevents horizontal scrolling */
    max-width: 100%;
    /* Ensures it does not exceed the parent container */
    box-sizing: border-box;
    /* Ensures padding doesn’t cause overflow */
}


.sub-category {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    width: 250px;
    height: 200px;
}




.sub-category .icon {
    width: 30px;
    height: 30px;
}

.sub-category .label {
    margin-top: 15px;
    font: 600;
    font-size: 16px;
}

.sub-category-image {
    width: 150px;
    height: 150px;
    border-radius: 40px;
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
