import { state } from "./state.js";
import { checkEmailUnique } from "./fake-server.js";
import { generateInternId } from "./rules-engine.js";
import { validateIntern } from "./validators.js";
import { renderInterns, renderError } from "./renderer.js";

document.getElementById("addIntern").addEventListener("click", async () => {
  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const skills = document.getElementById("skills").value
    .split(",")
    .map(s => s.trim())
    .filter(Boolean);

  try {
    renderError(null);

    validateIntern({ name, email, skills });

    await checkEmailUnique(email, state.interns);

    const intern = {
      id: generateInternId(state),
      name,
      email,
      skills,
      status: "ONBOARDING"
    };

    state.interns.push(intern);
    renderInterns(state.interns);

  } catch (err) {
    renderError(err.message);
  }
});






