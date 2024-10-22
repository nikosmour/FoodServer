// filters.js

// Capitalize the first letter of each word
export const capitalize = (value) => {
    if (!value) return '';
    return value.toString().replace(/\b\w/g, char => char.toUpperCase());
};

// Truncate text to a specified length
export const truncate = (text, length, suffix = '...') => {
    if (text.length <= length) {
        return text;
    }
    return text.substring(0, length) + suffix;
};

// Format numbers (add commas for thousands)
export const formatNumber = (value, decimalPlaces = 0) => {
    if (!value) return '';
    return Number(value).toLocaleString(undefined, {
        minimumFractionDigits: decimalPlaces,
        maximumFractionDigits: decimalPlaces
    });
};

// Format dates (e.g., "June 14, 2024")
export const formatDate = (value) => {
    if (!value) return '';
    const options = {year: 'numeric', month: 'long', day: 'numeric'};
    return new Date(value).toLocaleDateString(undefined, options);
};


// Create the plugin
export const FiltersPlugin = {
    install: (app, options) => {
        // Register each filter globally
        app.config.globalProperties.$capitalize = capitalize;
        app.config.globalProperties.$truncate = truncate;
        app.config.globalProperties.$formatNumber = formatNumber;
        app.config.globalProperties.$formatDate = formatDate;
    }
};
