// Array practices

const names = ["ram", "shyam", "gyan"];
// console.log(typeof(names));   // Object

// isArray() & instanceof
// console.log(Array.isArray(names));   // true

// console.log(names instanceof Array);

// splice mathod  1st parameter -> where to add and 2nd parameter -> how many  element should remove
const cars = ["maruti", "xuv", "fortuner", "i10"];
cars.splice(2, 0, "i20", "ertiga");

// console.log(cars);  // can change array also

// console.log(cars.includes("xuv"));   // element present or not

// console.log(cars.sort());  // soring element base on character

let nums = ["45", "87", "54", "22", "7", "9"];

// console.log(nums.sort());  // can't soring number value
// console.log(nums);    //  can change array also

let a = ["1", "2", "3"];

let b = ["4", "5", "6"];

// console.log(a.concat(b)); // concatination two arry but can't create new array

// console.log(b.concat(a)); // b will first and "a" is second

// to create an array from string or any

let text = "abcdefgh";

// console.log(Array.from(text));

// methods of arrays

let students = ["umang", "meet", "prince"];

// students.push("kush");
// console.log(students);  // add element at last

// students.pop();
// console.log(students);    //  remove element at last

// students.reverse(students);
// console.log(students);       // reverse element

// students.unshift("kush");    // add element at first
// console.log(students);

// students.shift();                // remove at first Element
// console.log(students);

let car = cars.entries();
for (let x of cars) {
  // console.log(x);
}

let nestedArr = [[2, 4], [6, 8], [10]]; // nested array [row][col]

// console.log(nestedArr);
// console.log(nestedArr[0][0]);

const color = ["red", "black", "green"]; // index 3 and 4 undefine but array size is 6

color[5] = "blue";

// console.log(color);
// console.log(color.length);

// console.log(color[4]);   //undefined

let fruits = ["Apple", "Banana", "Mango", "Orange"];

// Iteration using for loop
for (let i = 0; i < fruits.length; i++) {
  //   console.log(fruits[i]); // print each fruit
}

// for...of loop
for (let fruit of fruits) {
  //   console.log(fruit); // print each fruit
}

// forEach method
fruits.forEach(function (fruit) {
  //   console.log(fruit); // print each fruit
});

// Arrow function with forEach
fruits.forEach((fruit) => {
  //   console.log(fruit);
});

// -------------------OBJECT-------------------

const post = {
  username: "@umangparejiya",
  content: "This is my #firstPost",
  likes: 150,
  repost: 5,
  tegs: ["@alish", "@bob", "@eva"],
  view: function () {
    console.log(this.likes + " " + this.repost);
  }
};

// console.log(post);

//create an obj from existing obj

const update = Object.create(post);
update.likes = 200;

// console.log(post.likes + " " + update.likes);

// Object
// console.log(Object.keys(post));   // viewing keys in Object

let C = "content";

let L = "likes";

// console.log(post[C]+" "+"likes count "+post[L]);

// array of intren object

let intern = [
  { name: "bob", age: 19, role: "React" },
  { name: "alish", age: 20, role: "python" },
  { name: "eva", age: 18, role: "AI" }
];

// Print whole array
// console.log(intern);

// Print first student
// console.log(intern[0]);

// Print first student name
// console.log(intern[0].name);

// update object

post.likes = 300;

// console.log(post);

let resule = post;

// console.log(resule);  // show fully Object view

const data = {
  model: "xuv7OO",
  price: "23Lakhs",
  milege: "16km/L"
};

let txt = "";
for (let [data1, value] of Object.entries(data)) {
  //  Iterating over object entries using Object.entries()
  txt += data1 + " : " + value + ",  ";
}
console.log(txt);

// map method
let numbers = [1, 2, 3, 4, 5];
let cubeNumbers = numbers.map((num) => num * num * num);
// console.log(cubeNumbers);

// filter method
let evenNumbers = numbers.filter((num) => num % 2 === 0);
// console.log(evenNumbers);
