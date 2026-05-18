// Enhanced Admin Sidebar Functionality

document.addEventListener('DOMContentLoaded', function() {
    // Disable Bootstrap collapse behavior globally for sidebar
    disableBootstrapCollapse();
    
    // Initialize sidebar functionality
    initSidebar();
    
    // Initialize search functionality
    initSidebarSearch();
    
    // Set active menu based on current URL
    setActiveMenu();
    
    // Restore active state from localStorage
    restoreActiveState();
    
    // Initialize collapse states
    initCollapseStates();
});

// Disable Bootstrap collapse behavior for sidebar
function disableBootstrapCollapse() {
    // Remove data-bs-toggle attributes to prevent Bootstrap from handling them
    const collapseLinks = document.querySelectorAll('.sidenav .nav-link[data-bs-toggle="collapse"]');
    collapseLinks.forEach(link => {
        link.removeAttribute('data-bs-toggle');
        link.removeAttribute('data-bs-target');
        link.removeAttribute('aria-expanded');
        link.removeAttribute('aria-controls');
    });
    
    // Remove Bootstrap collapse classes and add our own
    const collapseElements = document.querySelectorAll('.sidenav .collapse');
    collapseElements.forEach(collapse => {
        collapse.classList.remove('collapse');
        collapse.classList.add('custom-collapse');
        collapse.style.display = 'none';
    });
}

function initCollapseStates() {
    // Ensure all collapse elements start in the correct state
    const collapseElements = document.querySelectorAll('.collapse');
    const menuLinks = document.querySelectorAll('.sidenav .nav-link[data-bs-toggle="collapse"]');
    
    collapseElements.forEach(collapse => {
        collapse.classList.remove('show');
    });
    
    menuLinks.forEach(link => {
        link.setAttribute('aria-expanded', 'false');
    });
}

function initSidebar() {
    // Handle menu item clicks with custom collapse behavior
    const menuItems = document.querySelectorAll('.sidenav .nav-link');
    
    menuItems.forEach(item => {
        // Check if this is a collapse trigger (has href="javascript:void(0);")
        if (item.getAttribute('href') === 'javascript:void(0);') {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Find the next sibling collapse element
                const nextElement = this.nextElementSibling;
                if (nextElement && nextElement.classList.contains('custom-collapse')) {
                    const isExpanded = nextElement.style.display === 'block';
                    
                    console.log('Menu clicked:', this.textContent.trim(), 'Expanded:', isExpanded);
                    
                    // Toggle current collapse without affecting others
                    if (isExpanded) {
                        nextElement.style.display = 'none';
                        this.classList.add('collapsed');
                        console.log('Menu collapsed:', this.textContent.trim());
                    } else {
                        nextElement.style.display = 'block';
                        this.classList.remove('collapsed');
                        console.log('Menu expanded:', this.textContent.trim());
                    }
                }
            });
        }
    });
    
    // Handle nested menu item clicks
    const nestedItems = document.querySelectorAll('.sidenav-menu-nested .nav-link');
    nestedItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all nested items
            nestedItems.forEach(nav => nav.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Store active state in localStorage
            localStorage.setItem('activeAdminMenuItem', this.getAttribute('href'));
        });
    });
    
    // Handle direct links (non-collapse items)
    const directLinks = document.querySelectorAll('.sidenav .nav-link[href]:not([href="javascript:void(0);"])');
    directLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Remove active class from all direct links
            directLinks.forEach(nav => nav.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Store active state in localStorage
            localStorage.setItem('activeAdminMenuItem', this.getAttribute('href'));
        });
    });
}

function initSidebarSearch() {
    const searchInput = document.getElementById('menu-search');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const menuItems = document.querySelectorAll('.sidenav .nav-link');
        const menuHeadings = document.querySelectorAll('.sidenav-menu-heading');
        
        if (searchTerm.length === 0) {
            // Show all items
            menuItems.forEach(item => {
                item.style.display = 'block';
                item.style.opacity = '1';
            });
            menuHeadings.forEach(heading => {
                heading.style.display = 'block';
                heading.style.opacity = '1';
            });
            return;
        }
        
        // Hide all headings initially
        menuHeadings.forEach(heading => {
            heading.style.display = 'none';
        });
        
        // Search through menu items
        menuItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            
            if (isVisible) {
                item.style.display = 'block';
                item.style.opacity = '1';
                
                // Show parent collapse if this is a nested item
                const parentCollapse = item.closest('.custom-collapse');
                if (parentCollapse) {
                    parentCollapse.style.display = 'block';
                    const parentLink = parentCollapse.previousElementSibling;
                    if (parentLink) {
                        parentLink.classList.remove('collapsed');
                    }
                }
            } else {
                item.style.opacity = '0.3';
            }
        });
    });
    
    // Clear search on escape key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            this.dispatchEvent(new Event('input'));
        }
    });
}

function setActiveMenu() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.sidenav .nav-link[href]');
    
    // Clear all active states first
    menuItems.forEach(nav => nav.classList.remove('active'));
    
    // Find the current active item
    let activeItem = null;
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPath.includes(href.replace('/admin/', ''))) {
            activeItem = item;
        }
    });
    
    if (activeItem) {
        // Add active class to current item
        activeItem.classList.add('active');
        
        // If this is a nested item, expand its parent and keep it open
        const parentCollapse = activeItem.closest('.custom-collapse');
        if (parentCollapse) {
            parentCollapse.style.display = 'block';
            const parentLink = parentCollapse.previousElementSibling;
            if (parentLink) {
                parentLink.classList.remove('collapsed');
            }
        }
        
        // Store active state
        localStorage.setItem('activeAdminMenuItem', activeItem.getAttribute('href'));
    }
    
    // Also handle direct links (non-collapse items)
    const directLinks = document.querySelectorAll('.sidenav .nav-link[href]:not([href="javascript:void(0);"])');
    directLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href.replace('/admin/', ''))) {
            link.classList.add('active');
        }
    });
}

// Restore active state from localStorage
function restoreActiveState() {
    const activeMenuItem = localStorage.getItem('activeAdminMenuItem');
    if (activeMenuItem) {
        const menuItems = document.querySelectorAll('.sidenav .nav-link[href]');
        menuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href === activeMenuItem) {
                item.classList.add('active');
                
                // Expand parent menu if it's a nested item
                const parentCollapse = item.closest('.custom-collapse');
                if (parentCollapse) {
                    parentCollapse.style.display = 'block';
                    const parentLink = parentCollapse.previousElementSibling;
                    if (parentLink) {
                        parentLink.classList.remove('collapsed');
                    }
                }
            }
        });
    }
    
    // Also restore direct links
    const directLinks = document.querySelectorAll('.sidenav .nav-link[href]:not([href="javascript:void(0);"])');
    directLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === activeMenuItem) {
            link.classList.add('active');
        }
    });
}

// Add smooth scrolling to sidebar
function smoothScrollToElement(element) {
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
}

// Keyboard navigation support
document.addEventListener('keydown', function(e) {
    if (e.altKey && e.key === 'm') {
        // Alt + M to focus search
        const searchInput = document.getElementById('menu-search');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// Auto-expand menu based on current page
function autoExpandCurrentMenu() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.sidenav .nav-link[href]');
    
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPath.includes(href.replace('/admin/', ''))) {
            const parentCollapse = item.closest('.custom-collapse');
            if (parentCollapse) {
                parentCollapse.style.display = 'block';
                const parentLink = parentCollapse.previousElementSibling;
                if (parentLink) {
                    parentLink.classList.remove('collapsed');
                }
            }
        }
    });
    
    // Also handle direct links
    const directLinks = document.querySelectorAll('.sidenav .nav-link[href]:not([href="javascript:void(0);"])');
    directLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href.replace('/admin/', ''))) {
            link.classList.add('active');
        }
    });
}

// Initialize auto-expand on page load
window.addEventListener('load', autoExpandCurrentMenu);

// Add hover effects for better UX
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.sidenav .nav-link');
    
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});
