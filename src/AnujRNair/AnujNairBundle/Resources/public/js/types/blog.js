import PropTypes from 'prop-types';

const Posts = PropTypes.arrayOf(
  PropTypes.shape({
    contents: PropTypes.string.isRequired,
    datePublished: PropTypes.shape({
      date: PropTypes.string.isRequired,
      timezone: PropTypes.string.isRequired,
      timezone_type: PropTypes.number.isRequired
    }).isRequired,
    dateUpdated: PropTypes.shape({
      date: PropTypes.string.isRequired,
      timezone: PropTypes.string.isRequired,
      timezone_type: PropTypes.number.isRequired
    }),
    id: PropTypes.number.isRequired,
    tagIds: PropTypes.arrayOf(PropTypes.number.isRequired),
    title: PropTypes.string.isRequired,
    userId: PropTypes.number.isRequired
  }).isRequired
);

const Archive = PropTypes.objectOf(
  PropTypes.objectOf(
    PropTypes.arrayOf(
      PropTypes.shape({
        id: PropTypes.number.isRequired,
        title: PropTypes.string.isRequired,
        urlTitle: PropTypes.string.isRequired
      }).isRequired
    ).isRequired
  ).isRequired
);

const TagSummary = PropTypes.arrayOf(
  PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
    tagCount: PropTypes.string.isRequired
  })
);

export { Archive, Posts, TagSummary };
