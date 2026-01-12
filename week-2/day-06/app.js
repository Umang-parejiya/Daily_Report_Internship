// TYPE OD JAVASCRIPT

console.log(typeof 23); // number
console.log(typeof "umang"); // String
console.log(typeof null); // object
console.log(typeof undefined); // undefined
console.log(typeof Function); // function

// JavaScript Operators
console.log("JavaScript Operators");

let a = 10,
  b = 2;

console.log(a + b);
console.log(a - b);
console.log(a * b);
console.log(a / b);
console.log(a % b);
console.log(a ** b);
console.log("UMANG" + " " + "PAREJIYA"); // Concetination of String
console.log(2 + 6 + "6"); // 86

console.log((2 * 3) / 2 - 2 + 10 ** 2);

// Comparison Operators
console.log("Comparison Operators");

let x = 10,
  y = 14;

console.log(a == b);
console.log(a === b);
console.log(a != b);
console.log(a > b);
console.log(a < b);
console.log(a >= b);
console.log(a <= b);

// Logical Operators
console.log("Logical Operators");

console.log(34 > 23 && 3 == "3"); // true
console.log(20 <= 23 && 3 === "3"); //false

console.log(34 < 23 || 3 == "3"); // true
console.log(20 >= 23 && 3 === "3"); // false

let p = 10;

console.log(p++);
console.log(++p);

// one Operant using

console.log((p += 5));
console.log((p -= 5));
console.log((p *= 5));
console.log((p /= 5));
console.log((p %= 5));

const f = { country: "india" };
const e = { country: "india" };

console.log(f === e); /// comparision by pass by refrence

// Example of null toggle
let input = null;

if (!input) {
  console.log("Input missing");
}

console.log("ternary condition");

let age = 23;
let result = age > 18 ? "Adult" : "child"; // Adult

let gender = "male";

let findGender =
  gender == "male"
    ? "male"
    : gender == "female"
    ? "female" // multiple condition ternary
    : "other";

// Conditional Logic Programs practice
console.log("Conditional Logic Programs practice");

// Largest of Two Numbers
console.log("Largest of Two Numbers");

let t = 10,
  s = 20;

if (t > s) {
  console.log("A is greater");
} else {
  console.log("B is greater");
}

// Positive, Negative or Zero

console.log("Positive, Negative or Zero");

let num = -5;

if (num > 0) {
  console.log("Positive");
} else if (num < 0) {
  console.log("Negative");
} else {
  console.log("Zero");
}

// Password Validation

console.log("Password Validation");

let password = "12345";

if (password.length >= 8) {
  console.log("Strong password");
} else {
  console.log("Weak password");
}

// if–else Statement (Decision Making)

// Syntax

// if (condition) {
//   // runs if condition is true
// } else {
//   // runs if condition is false
// }

// ODD OR EVEN
console.log("ODD OR EVEN");
let number = 7;

if (number % 2 === 0) {
  console.log("Even number");
} else {
  console.log("Odd number");
}

//GRADING SYSTEM
console.log("GRADING SYSTEM");

let marks = 85;

if (marks >= 90) {
  console.log("Grade O");
} else if (marks >= 75) {
  console.log("Grade A");
} else if (marks >= 60) {
  console.log("Grade B");
} else if (marks >= 40) {
  console.log("Grade C");
} else {
  console.log("Fail");
}

// switch–case (Multiple Fixed Conditions)
console.log("switch–case (Multiple Fixed Conditions)");

const day = 3;

switch (day) {
  case 1:
    console.log("Monday");
    break;
  case 2:
    console.log("Tuesday");
    break;
  case 3:
    console.log("Wednesday");
    break;
  case 4:
    console.log("Thresday");
    break;
  case 5:
    console.log("FrIdaY");
    break;
  case 6:
    console.log("Saturday");
    break;
  case 7:
    console.log("Sunday");
    break;
  default:
    console.log("Invalid day");
}
let light = "red";

switch (light) {
  case light:
    console.log("Stoped");
    break;
  case light:
    console.log("slowDown");
    break;
  case light:
    console.log("go");
    break;
  default:
    console.log("invalid light");
}

let value = 5 / "fefe";

if (Number.isNaN(value)) {
  console.log("Value is NaN");
} else {
  console.log("Value is a number");
}

// Max and Min bitween value

let min = 10;
let max = 20;
let g = Math.floor(Math.random() * (max - min + 1) + min);
console.log("Random pic value bitween 10 to 20", g);

//Check if Value is NaN (NO built-in function)

let value1 = NaN;

if (value1 !== value1) {
  console.log("Value is NaN");
} else {
  console.log("Value is NOT NaN");
}

// Check if Value is a Pure Number (NO built-in)

let value3 = 100;

if (typeof value3 === "number" && value3 === value3) {
  console.log("Valid number");
} else {
  console.log("Not a valid number");
}

// Check Numeric or Not (String Included, NO built-in)

let value4 = "asd";

if (value4 == value4 && value4 - value4 === 0) {
  console.log("Numeric value");
} else {
  console.log("Not numeric");
}

// without inbuild function check NaN

let value5 = 0 / 0;

if (value5 !== value5) {
  console.log("NaN value");
} else if (typeof value5 === "number") {
  console.log("Pure number");
} else if (value5 - value5 === 0) {
  console.log("Numeric string");
} else {
  console.log("Not a number");
}

// Excaption
console.log("" - "" === 0); // true
