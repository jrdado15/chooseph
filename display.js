const displayList = document.querySelector(".display-info");
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "display.php?minAge=" + minAge + "&maxAge=" + maxAge + "&sex=" + sex, true);
  xhr.onload = ()=> {
    if(xhr.readyState === XMLHttpRequest.DONE) {
      if(xhr.status === 200) {
        let data = xhr.response;
        displayList.innerHTML = data;
      }
    }
  }
  xhr.send();
}, 1000);