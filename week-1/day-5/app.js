console.log("it's External javascript");

// Variable Declarations in JavaScript

var fname = "umang";
console.log(fname);

let city = "Gandhinagar";
city = "surat";

//Can be updated
console.log(city);

const country = "India";
//  country = "Englend"

// can't be updated
console.log(country); // throw Error

const name = "parejiya";

console.log(`Welcome ${name}`);

//Object
let user = {
  name: "Umang",
  age: 21
};
console.log(user);

console.log(5 + 6);

let n = null;

console.log(typeof n);

// console type

console.table([{ name: "Umang", age: 21 }]);
console.info("User logged in successfully");
console.dir(document.body);

function sum(n) {
  let total = 0;
  for (let i = 1; i <= n; i++) {
    total += i;
  }
  return total;
}

console.time("Sum Function");
sum(1000000);
console.timeEnd("Sum Function");
console.log(sum(10));

console.group("User Details");
console.log("Name: Umang");
console.log("Age: 21");
console.groupEnd();

console.warn("Warning message");
console.error("Error message");
