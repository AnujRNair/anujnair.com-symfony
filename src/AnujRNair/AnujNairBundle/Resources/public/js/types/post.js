import PropTypes from 'prop-types';

const Post = PropTypes.shape({
  contents: PropTypes.string.isRequired,
  datePublished: PropTypes.string.isRequired,
  id: PropTypes.number.isRequired,
  tagIds: PropTypes.arrayOf(PropTypes.number.isRequired),
  title: PropTypes.string.isRequired,
  userId: PropTypes.number.isRequired,
  urlTitle: PropTypes.string.isRequired,
});

const Posts = PropTypes.arrayOf(Post);

const Archive = PropTypes.objectOf(
  PropTypes.objectOf(
    PropTypes.arrayOf(
      PropTypes.shape({
        id: PropTypes.number.isRequired,
        title: PropTypes.string.isRequired,
        urlTitle: PropTypes.string.isRequired,
      }).isRequired
    ).isRequired
  ).isRequired
);

const Similar = PropTypes.shape({
  'Extra Reading': Posts,
  'Similar Blog Posts': Posts,
});

export { Archive, Post, Posts, Similar };
