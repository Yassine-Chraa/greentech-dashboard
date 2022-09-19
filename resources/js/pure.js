var links = document.querySelectorAll(".sidebar >nav ul .nav-item .nav-link");
function setActiveClass() {
  let list = document.querySelectorAll(
    ".nav-item .nav-treeview .nav-item .nav-link"
  );
  for (let i = 0; i < links.length; i++) {
    links[i].classList.remove("active");
  }
  let link = document.getElementsByClassName("router-link-active")[0];
  link.classList += " active";

  for (let i = 0; i < list.length; i++) {
    if (list[i].classList.contains("router-link-active")) {
      document.querySelector(
        ".nav-item .nav-treeview"
      ).parentElement.children[0].classList += " active";
    }
  }
}
if (
  document.querySelectorAll(".sidebar >nav ul .nav-item .nav-link").length != 0
) {
  window.onload = setActiveClass();
  for (let i = 0; i < links.length; i++) {
    links[i].addEventListener("click", setActiveClass);
  }
}


