const displayImageOneList = document.querySelector(".display-image-1");
const displayImageTwoList = document.querySelector(".display-image-2");
const displayImageThreeList = document.querySelector(".display-image-3");
const displayImageFourList = document.querySelector(".display-image-4");

setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "display-images.php?num=1&" + "minAge=" + minAge + "&maxAge=" + maxAge + "&sex=" + sex, true);
  xhr.onload = ()=> {
    if(xhr.readyState === XMLHttpRequest.DONE) {
      if(xhr.status === 200) {
        let data = xhr.response;
        displayImageOneList.innerHTML = data;
      }
    }
  }
  xhr.send();

  let xhr2 = new XMLHttpRequest();
  xhr2.open("GET", "display-images.php?num=2&" + "minAge=" + minAge + "&maxAge=" + maxAge + "&sex=" + sex, true);
  xhr2.onload = ()=> {
    if(xhr2.readyState === XMLHttpRequest.DONE) {
      if(xhr2.status === 200) {
        let data2 = xhr2.response;
        displayImageTwoList.innerHTML = data2;
      }
    }
  }
  xhr2.send();
  
  let xhr3 = new XMLHttpRequest();
  xhr3.open("GET", "display-images.php?num=3&" + "minAge=" + minAge + "&maxAge=" + maxAge + "&sex=" + sex, true);
  xhr3.onload = ()=> {
    if(xhr3.readyState === XMLHttpRequest.DONE) {
      if(xhr3.status === 200) {
        let data3 = xhr3.response;
        displayImageThreeList.innerHTML = data3;
      }
    }
  }
  xhr3.send();
  
  let xhr4 = new XMLHttpRequest();
  xhr4.open("GET", "display-images.php?num=4&" + "minAge=" + minAge + "&maxAge=" + maxAge + "&sex=" + sex, true);
  xhr4.onload = ()=> {
    if(xhr4.readyState === XMLHttpRequest.DONE) {
      if(xhr4.status === 200) {
        let data4 = xhr4.response;
        displayImageFourList.innerHTML = data4;
      }
    }
  }
  xhr4.send();
}, 500);