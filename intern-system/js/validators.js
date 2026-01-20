export function validateIntern({ name, email, skills }) {
  if (!name || !email) {
    throw new Error("Name and Email are required");
  }
  if (!skills.length) {
    throw new Error("At least one skill required");
  }
}
