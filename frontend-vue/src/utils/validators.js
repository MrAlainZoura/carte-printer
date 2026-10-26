export function required(value) {
  return String(value ?? '').trim().length > 0
}

export function validEmail(value) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value ?? ''))
}
