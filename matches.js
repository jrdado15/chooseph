const matchesList = document.querySelector(".matches-list");
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "matches.php", true);
  xhr.onload = ()=> {
    if(xhr.readyState === XMLHttpRequest.DONE) {
      if(xhr.status === 200) {
        let data = xhr.response;
        matchesList.innerHTML = data;
      }
    }
  }
  xhr.send();
}, 500);