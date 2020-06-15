import PropTypes from 'prop-types';

const ContactForm = PropTypes.shape({
  csrf: PropTypes.string.isRequired,
  values: PropTypes.shape({
    name: PropTypes.string,
    email: PropTypes.string,
    subject: PropTypes.string,
    contents: PropTypes.string,
  }).isRequired,
  errors: PropTypes.shape({
    _form: PropTypes.arrayOf(PropTypes.string),
    name: PropTypes.arrayOf(PropTypes.string),
    email: PropTypes.arrayOf(PropTypes.string),
    subject: PropTypes.arrayOf(PropTypes.string),
    contents: PropTypes.arrayOf(PropTypes.string),
  }).isRequired,
});

/* eslint-disable-next-line import/prefer-default-export */
export { ContactForm };
