const conversationsList = document.querySelector(".conversations-list");
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "conversations.php", true);
  xhr.onload = ()=> {
    if(xhr.readyState === XMLHttpRequest.DONE) {
      if(xhr.status === 200) {
        let data = xhr.response;
        conversationsList.innerHTML = data;
      }
    }
  }
  xhr.send();
}, 500);