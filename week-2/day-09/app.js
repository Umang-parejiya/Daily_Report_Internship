// Access a paragraph Element
// const element = document.getElementById("intro");

// Change the content of the Element
// document.getElementById("demo").innerHTML = "this text from the intro paragraph is : "+element.innerHTML;


// Access a paragraph Element by TegName
const tegName = document.getElementsByTagName("p");

//changing data of p teg 

tegName[0].innerText = "changing one paragraph by tegName" //all p tegs become array and in accesing by it's index

// Access a paragraph Element by class name

const className = document.getElementsByClassName("pClass");

className[0].innerHTML = "changing one paragraph by className "


// Access a h3 Element by querySelector()

const qsc = document.querySelector(".qsc");
qsc.innerHTML = "changing para text by queryselector"

// Access a h3 Element by querySelectorAll()

const qsa = document.querySelectorAll(".btn");
    qsa.forEach(btn=>{
        btn.style.border= "2px solid black"     //  forEach method using with btn array
    });


    // method fo appendChild() add at last 
// let div = document.createElement("div");
// div.innerText = "Hello DOM";

// document.body.appendChild(div);

// let paragraph = document.createElement("p");
// paragraph.innerText = "this is paragraph teg"

// let paragraph2 = document.createElement("p");
// paragraph2.innerText = "this is paragraph teg 2"

// document.body.appendChild(paragraph);
// document.body.appendChild(paragraph2);

// // paragraph.insertBefore(div,paragraph.children)  

//  let para = document.getElementById("removeMe");
// document.body.removeChild(para);

// // replaceChild()  

// let newEl = document.createElement("h2");
// newEl.innerText = "Replaced Content";

// let oldEl = document.getElementById("old");

// document.body.replaceChild(newEl, oldEl);

// form submition data
const x = document.forms["frm"];
let text = "";

for(let i=0;i<x.length;i++){
    text += x.elements[i].value + " <br>";
}
document.getElementById("demo").innerHTML = text;


// document.getElementById(yt).src ="w3s.png";    // changing html img teg

document.getElementById("dt").innerHTML = "Date : " + Date();

document.getElementById("chnColr").style.color = "blue";
document.getElementById("chnFf").style.fontFamily = "Arial";
document.getElementById("chnFf").style.fontSize = "larger";



function myFunction() {
  // Get the value of the input field with id="numb"
  let x = document.getElementById("numb").value;
  // If x is Not a Number or less than one or greater than 10
  let text;
  if (isNaN(x) || x < 1 || x > 10) {
    text = "Input not valid";
  } else {
    text = "Input OK";
  }
   document.getElementById("result").innerHTML = text;
}


// // setinterval

let myInterval;
let count = 0;

const btnStart = document.getElementById("start");
const btnStop = document.getElementById("stop");


// let btnStart listen for a click
btnStart.addEventListener("click", function () {
  myInterval = setInterval(counter, 1000);
});

// let btnStop listen for a click
btnStop.addEventListener("click", function () {
  clearInterval(myInterval);   // clearInterval
});
function counter() {
  count++;
  document.getElementById("counter").innerHTML = count;
}

