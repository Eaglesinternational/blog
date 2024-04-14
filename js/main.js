const navItems = document.querySelector('.nav__items');
const openNavBtn = document.querySelector('#open__nav-btn');
const closeNavBtn = document.querySelector('#close__nav-btn');

const openNav = () => {
    navItems.style.display='flex';
    openNavBtn.style.display ='none';
    closeNavBtn.style.display='inline-block';
}
const closeNav = () =>{
    navItems.style.display='none';
    openNavBtn.style.display ='inline-block';
    closeNavBtn.style.display='none';
    
}
openNavBtn.addEventListener('click', openNav);
closeNavBtn.addEventListener('click', closeNav);

/********************SIDEBAR****************** */
const sidebar = document.querySelector('aside');
const showSidebarBtn = document.querySelector('#show__sidebar-btn');
const hideSidebarBtn = document.querySelector('#hide__sidebar-btn');

const showSidebar= ()=>{
    sidebar.style.left='0';
    hideSidebarBtn.style.display='block';
    showSidebarBtn.style.display='none';
}
const hideSidebar= ()=>{
    sidebar.style.left='-100%';
    hideSidebarBtn.style.display='none';
    showSidebarBtn.style.display='block';
}
showSidebarBtn.addEventListener('click', showSidebar);
hideSidebarBtn.addEventListener('click', hideSidebar);