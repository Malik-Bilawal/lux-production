const THEME_COLORS_DATA = {
    brand: {
        // ... other colors
        black: '#000000',       
        dark: '#121212',        
        surface: '#1E1E1E',     

        // 2. TYPOGRAPHY
        gray: '#E5E7EB',        
        white: '#FFFFFF',    
        muted: '#9CA3AF',   
        
        // 👇 YOU WERE MISSING THIS KEY
        gold: '#D9FF33', // Add this line! (Or pick your main gold color)
        
        'gold-light': '#D9FF33', 
        'gold-dark': '#B3E600',      
    }
};

const SWEETALERT_COLOR_CONFIG = {
    background: THEME_COLORS_DATA.brand.surface, 
    color: THEME_COLORS_DATA.brand.gray,
    // Now this will work because .gold exists
    confirmButtonColor: THEME_COLORS_DATA.brand.gold, 
    cancelButtonColor: THEME_COLORS_DATA.brand.muted,
};

window.SWEETALERT_COLORS = SWEETALERT_COLOR_CONFIG;