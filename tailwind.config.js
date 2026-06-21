import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                "surface-dim": "#d8dadc",
                "error-container": "#ffdad6",
                "on-surface": "#191c1e",
                "on-surface-variant": "#45464d",
                "surface": "#f7f9fb",
                "on-error-container": "#93000a",
                "primary-container": "#131b2e",
                "on-secondary": "#ffffff",
                "background": "#f7f9fb",
                "on-primary": "#ffffff",
                "outline-variant": "#c6c6cd",
                "on-background": "#191c1e",
                "error": "#ba1a1a",
                "on-secondary-fixed-variant": "#005236",
                "secondary-fixed-dim": "#4edea3",
                "inverse-on-surface": "#eff1f3",
                "surface-container-high": "#e6e8ea",
                "on-primary-fixed": "#131b2e",
                "primary-fixed-dim": "#bec6e0",
                "secondary": "#006c49",
                "on-tertiary-fixed-variant": "#38485d",
                "on-tertiary-fixed": "#0b1c30",
                "outline": "#76777d",
                "on-tertiary-container": "#75859d",
                "tertiary-container": "#0b1c30",
                "surface-tint": "#565e74",
                "on-secondary-container": "#00714d",
                "on-tertiary": "#ffffff",
                "surface-container": "#eceef0",
                "surface-container-low": "#f2f4f6",
                "tertiary-fixed": "#d3e4fe",
                "surface-container-lowest": "#ffffff",
                "inverse-primary": "#bec6e0",
                "on-secondary-fixed": "#002113",
                "primary": "#000000",
                "surface-bright": "#f7f9fb",
                "on-primary-fixed-variant": "#3f465c",
                "on-error": "#ffffff",
                "surface-container-highest": "#e0e3e5",
                "primary-fixed": "#dae2fd",
                "surface-variant": "#e0e3e5",
                "secondary-container": "#6cf8bb",
                "secondary-fixed": "#6ffbbe",
                "inverse-surface": "#2d3133",
                "on-primary-container": "#7c839b",
                "tertiary": "#000000",
                "tertiary-fixed-dim": "#b7c8e1"
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
            spacing: {
                "base": "4px",
                "2xl": "48px",
                "sm": "8px",
                "container-max": "1280px",
                "xs": "4px",
                "gutter": "16px",
                "margin-mobile": "16px",
                "margin-desktop": "32px",
                "xl": "32px",
                "md": "16px",
                "lg": "24px"
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                "headline-md": ["Inter"],
                "headline-lg-mobile": ["Inter"],
                "headline-lg": ["Inter"],
                "body-sm": ["Inter"],
                "label-md": ["Inter"],
                "display-lg": ["Inter"],
                "label-sm": ["Inter"],
                "body-lg": ["Inter"],
                "body-md": ["Inter"]
            },
            fontSize: {
                "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                "label-md": ["14px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "500" }],
                "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }]
            }
        },
    },

    plugins: [forms, require('@tailwindcss/container-queries')],
};
