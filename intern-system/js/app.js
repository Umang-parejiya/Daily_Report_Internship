import { state } from "./state.js";
import { checkEmailUnique } from "./fake-server.js";
import { generateInternId } from "./rules-engine.js";
import { validateIntern } from "./validators.js";
import { renderInterns, renderError } from "./renderer.js";

document.getElementById("addIntern").addEventListener("click", async () => {
  // Grab input fields
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const skillsInput = document.getElementById("skills");

  const name = nameInput.value.trim();
  const email = emailInput.value.trim();
  const skills = skillsInput.value
    .split(",")
    .map(s => s.trim())
    .filter(Boolean);

  try {
    renderError(null); // Clear previous error

    // Your validation function (throws error if invalid)
    validateIntern({ name, email, skills });

    // Async check for email uniqueness
    await checkEmailUnique(email, state.interns);

    // Create intern object
    const intern = {
      id: generateInternId(state),
      name,
      email,
      skills,
      status: "ONBOARDING"
    };

    // Add to state
    state.interns.push(intern);

    // ✅ Clear input fields after successful addition
    nameInput.value = "";
    emailInput.value = "";
    skillsInput.value = "";

    // Render updated intern list
    renderInterns(state.interns);

  } catch (err) {
    renderError(err.message);
  }
});




