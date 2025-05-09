function init() {
    console.log('Initializing document....');
    showMenu();
}

function showMenu() {
    var parent = document.getElementById('menu');
    menu.forEach(item => {
        var divItem = createMenuItem(item);
        
        divItem.addEventListener('click', () => {
            if (item.subItems.length > 0) {
                toggleSubMenu(divItem, item.subItems);
            } else {
                window.location.href = item.url;
            }
        });

        parent.appendChild(divItem);
    });
}

function createMenuItem(item) {
    console.log(item);

    var divItem = document.createElement('div');
    divItem.id = 'div-menu-item-' + item.id;
    divItem.className = 'menu-item';

    var divIcon = document.createElement('div');
    divIcon.className = 'menu-item-icon';
    divIcon.style.background = item.color;
    divItem.appendChild(divIcon);

    var icon = document.createElement('i');
    icon.className = 'fas fa-' + item.icon;
    divIcon.appendChild(icon);

    var label = document.createElement('label');
    label.textContent = item.title;
    divItem.appendChild(label);

    return divItem;
}

function toggleSubMenu(parentDiv, subItems) {
    var existingSubMenu = parentDiv.nextElementSibling;
    if (existingSubMenu && existingSubMenu.classList.contains('sub-menu')) {
        existingSubMenu.remove();
        parentDiv.classList.remove('active');
    } else {
        var subMenu = document.createElement('div');
        subMenu.className = 'sub-menu';

        subItems.forEach(subItem => {
            var divSubItem = document.createElement('div');
            divSubItem.className = 'sub-menu-item';
            divSubItem.textContent = subItem.title;

            divSubItem.addEventListener('click', (event) => {
                event.stopPropagation();
                window.location.href = subItem.url;
            });

            subMenu.appendChild(divSubItem);
        });

        parentDiv.parentNode.insertBefore(subMenu, parentDiv.nextSibling);
        parentDiv.classList.add('active');
    }
}
