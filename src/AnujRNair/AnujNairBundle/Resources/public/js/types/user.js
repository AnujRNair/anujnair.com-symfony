import PropTypes from 'prop-types';

const User = PropTypes.shape({
  id: PropTypes.number.isRequired,
  firstName: PropTypes.string.isRequired,
  lastName: PropTypes.string.isRequired
});

const Users = PropTypes.arrayOf(User);

export { User, Users };
