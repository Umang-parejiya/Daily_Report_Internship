let count = 0;

const countEl = document.getElementById("count");
const incBtn = document.getElementById("inc");
const decBtn = document.getElementById("dec");
const themeBtn = document.getElementById("theme");

incBtn.addEventListener("click", function () {
  count++;
  countEl.innerText = count;
});
decBtn.addEventListener("click", function () {
  count--;
  countEl.innerText = count;
});

themeBtn.addEventListener("click", function () {
if (document.body.style.backgroundColor === "black") {
  document.body.style.backgroundColor = "white";
  document.body.style.color = "black";
} else {
  document.body.style.backgroundColor = "black";
  document.body.style.color = "white";
}

});

