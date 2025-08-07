import twAnimateCss from 'tw-animate-css';

export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.ts',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    twAnimateCss,
  ],
};
