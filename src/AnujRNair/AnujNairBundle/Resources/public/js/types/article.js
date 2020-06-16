import PropTypes from 'prop-types';

const Article = PropTypes.shape({
  contents: PropTypes.string.isRequired,
  dateCreated: PropTypes.string.isRequired,
  id: PropTypes.number.isRequired,
  image: PropTypes.string.isRequired,
  link: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  tagIds: PropTypes.arrayOf(PropTypes.number.isRequired),
  urlTitle: PropTypes.string.isRequired,
});

const Articles = PropTypes.arrayOf(Article);

export { Article, Articles };
