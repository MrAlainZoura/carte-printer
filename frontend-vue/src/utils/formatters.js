export function formatDate(value) {
  if (!value) return '—'
  return new Intl.DateTimeFormat('fr-FR').format(new Date(value))
}

export function formatNumber(value) {
  return new Intl.NumberFormat('fr-FR').format(Number(value || 0))
}
