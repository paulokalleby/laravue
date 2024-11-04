import { helpers, maxLength, minLength, required } from "@vuelidate/validators";

const brandsUpdateRules = {
  data: {
    name: {
      required: helpers.withMessage("Informe o nome", required),
      minLength: helpers.withMessage(
        "O nome deve conter um mínimo de 3 dígitos",
        minLength(3)
      ),
      maxLength: helpers.withMessage(
        "O nome deve conter um máximo de 50 dígitos",
        maxLength(100)
      ),
    },
  },
};

export default brandsUpdateRules;
