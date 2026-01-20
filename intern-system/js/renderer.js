export function renderInterns(interns) {
  const ul = document.getElementById("internList");
  ul.innerHTML = "";

  interns.forEach(i => {
    const li = document.createElement("li");
    li.innerText = `${i.id} | ${i.name} | ${i.status} | Skills: ${i.skills.join(", ")}`;
    ul.appendChild(li);
  });
}

export function renderError(error) {
  document.getElementById("error").innerText = error || "";
}
