// Day_07 internship working

// for loop

for (let i = 1; i <= 3; i++) {
  //   console.log(i);
}

// breaklable statement

loop1: for (i = 1; i <= 5; i++) {
  loop2: for (j = 1; j <= 10; j++) {
    if (i === 3) {
      break loop1;
    }
    // console.log(i+"*"+j+"="+i*j)
  }
}
// for of loop mainly use in array ,String
let arr = [10, 20, 30];

for (let value of arr) {
  //   console.log(value);
}
let str = "JavaScript";

for (let ch of str) {
  // console.log(ch);
}

// forEach() loop   only array using
let arr1 = [12, 24, 36, 48];

arr1.forEach(function (value, index) {
  // console.log(value,"[",index,"]");
});

// for in loop  object friendly using
let mySelf = {
  name: "umang parejiya",
  age: 21,
  country: "india"
};

for (let key in mySelf) {
  // console.log(key,":-",mySelf[key]);
}

// real example of all three

let arr2 = [1, 2, 3];
let obj = { a: 10, b: 20 };

// for...of
let mul1 = 1;
for (let v of arr2) {
  mul1 *= v;
}
console.log(mul1);

// forEach
// arr2.forEach((v,index) => console.log(v,"[",index,"]"));

// for...in
for (let k in obj) {
  //   console.log(k, obj[k]);
}

// while loop

let a = 1;
while (a <= 5) {
  // console.log(a);
  a++;
}

// Do while loop

let b = 10;
do {
  // console.log(b);
  b--;
} while (b >= 1);

// loop control statements

// break
for (c = 0; c <= 5; c++) {
  if (c === 4) break;
  // console.log(c);
}

// continue
for (i = 0; i <= 5; i++) {
  if (i == 3) continue;
  // console.log(i);
}

//Functions (Reusable Logic)

function toCelsius(fahrenheit) {
  return (5 / 9) * (fahrenheit - 32);
}
// console.log(toCelsius(77)+"*C");
// toCelsius(100)
// toCelsius(104)

// Arrow Functions Short function syntax

let myFunction = (a, b) => {
  return a * b;
};

// console.log(myFunction(2,6));

function addOpra(x, y = 10) {
  /// parameter value  decleration
  return x + y;
}
// console.log(addOpra(5));

// Function Rest Parameter  indefine number Arguments

function sum(...args) {
  let sum = 0;
  for (let arg of args) {
    sum += arg;
  }
  return sum;
}
// console.log(sum(2,4,6,8,10));

// The Arguments Object

function maxNum() {
  let max = -Infinity;
  for (let i = 0; i < arguments.length; i++) {
    if (arguments[i] > max) {
      max = arguments[i];
    }
  }
  return max;
}

// console.log(maxNum(1, 123, 500, 115, 44, 88));

// call by value

function myFunc(theObject) {
  theObject.make = "Hero";
}

const myBike = {
  make: "Honda",
  model: "sp125",
  year: 2024
};

// console.log(myBike.make); // "Honda"
myFunc(myBike);
// console.log(myBike.make); // "Hero"

const factorial = function fac(n) {
  return n < 2 ? 1 : n * fac(n - 1);
};

// console.log(factorial(3)); // 6

// function scope

// The outer function defines a variable called "name"
const pet = function (name) {
  const getName = function () {
    // The inner function has access to the "name" variable of the outer function
    return name;
  };
  return getName; // Return the inner function, thereby exposing it to outer scopes
};
const myPet = pet("perot");

// console.log(myPet()); // "perot"

// closures concept of Nested function
function A(x) {
  function B(y) {
    function C(z) {
      //   console.log(x + y + z);
    }
    C(3);
  }
  B(2);
}
A(1); // Logs 6 (which is 1 + 2 + 3)

//  Anonymous function

const x = function (a, b) {
  return a * b;
}; // without function name

// console.log(x(4,3));

// Arrow Function

// without function keyword, the return keyword, and the curly brackets

let addNum = (a, b) => a + b;

// console.log(addNum(2,4));

let mul = (a, b, c) => {
  return a * b * c;
};
// console.log(mul(2,5,9));

// Return Values

function checkAge(age) {
  // multiple return
  if (age >= 18) {
    return "Adult";
  }
  return "Minor";
}

// for Practice reverse String

function reverseStrign(str1) {
  let reverse = "";

  for (i = str1.length - 1; i >= 0; i--) {
    reverse += str1[i];
  }
  return reverse;
}

// console.log(reverseStrign("javascript"));

function printStars(u) {
  for (let i = 1; i <= u; i++) {
    let row = "";

    for (let j = 1; j <= i; j++) {
      row += "* ";
    }

    console.log(row);
  }
}
// printStars(5)

function skipNumber(n, skip) {
  for (let i = 1; i <= n; i++) {
    if (i === skip) continue;
    console.log(i);
  }
}

// skipNumber(5, 3);

// console.log(skipNumber.toString());  // toString concept

let user = {
  name: "Umang",
  age: 22
};
console.log(Object.keys(user)); //
