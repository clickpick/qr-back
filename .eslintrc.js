module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
    es6: true
  },
  parser: "vue-eslint-parser",
  parserOptions: {
    parser: "babel-eslint",
    ecmaVersion: 2019,
    sourceType: "module",
    ecmaFeatures: {
      experimentalObjectRestSpread: true,
      jsx: true
    }
  },
  extends: [
    "eslint:recommended",
    "problems"
  ],
  plugins: [
    "import",
    "promise",
    "vue"
  ],
  rules: {
    "indent": [
      2,
      2,
      {
        "VariableDeclarator": "first",
        "FunctionDeclaration": {
          "parameters": "first"
        },
        "CallExpression": {
          "arguments": "first"
        },
        "ArrayExpression": 1,
        "ObjectExpression": 1,
        "SwitchCase": 1
      }
    ],
    "array-bracket-spacing": [2, "never"],
    "object-curly-spacing": [2, "always", { "objectsInObjects": false, "arraysInObjects": false }],
    "computed-property-spacing": [2, "never"],
    "space-in-parens": [2, "never"],
    "comma-spacing": [2, { "before": false, "after": true }],
    "quotes": [2, "double", { "allowTemplateLiterals": true }],
    "no-undef-init": 2,
    "no-void": 2,
    "no-undefined": 2,
    "no-shadow-restricted-names": 2
  }
}
