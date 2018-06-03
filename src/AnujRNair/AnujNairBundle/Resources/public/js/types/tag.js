import PropTypes from 'prop-types';

const Tag = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  urlName: PropTypes.string.isRequired
});

const Tags = PropTypes.arrayOf(Tag);

const TagSummary = PropTypes.arrayOf(
  PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
    tagCount: PropTypes.string.isRequired
  })
);

export { Tag, Tags, TagSummary };
