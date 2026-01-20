export function generateInternId(state) {
  const year = new Date().getFullYear();
  return `${year}-${state.counters.internSeq++}`;
}

export function canChangeStatus(from, to) {
  const rules = {
    ONBOARDING: ["ACTIVE"],
    ACTIVE: ["EXITED"],
    EXITED: []
  };
  return rules[from]?.includes(to);
}