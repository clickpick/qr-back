export const parse = (e) => {
  if (e && e.response && e.response.data && e.response.data.errors) {
    const { errors } = e.response.data;
    let current;
    return Object.keys(errors).reduce((acc, name) => {
      current = errors[name];
      if (current) {
        if (Array.isArray(current)) {
          acc.push(...current);
        } else {
          acc.push(String(current));
        }
      }
      return acc;
    }, []).join("\r\n");
  }
  return e && e.message || String(e);
};
