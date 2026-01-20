export function simulateAsync(fn, delay = 700) {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      try {
        resolve(fn());
      } catch (err) {
        reject(err);
      }
    }, delay);
  });
}

export function checkEmailUnique(email, interns) {
  return simulateAsync(() => {
    if (interns.some(i => i.email === email)) {
      throw new Error("Email already exists");
    }
    return true;
  });
}
