import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';
import Prism from 'prismjs';
import 'prismjs/plugins/line-numbers/prism-line-numbers';

import {
  Archive,
  Posts,
  TagSummary as TagSummaryProps
} from '@anujnair/js/types/post';
import { Tags } from '@anujnair/js/types/tag';
import { Users } from '@anujnair/js/types/user';

import Article from '@anujnair/js/components/article/article.jsx';
import BlogArchive from '@anujnair/js/components/blog-archive/blog-archive.jsx';
import TagSummary from '@anujnair/js/components/tag-summary/tag-summary.jsx';

export default class BlogIndex extends Component {
  static propTypes = {
    page: PropTypes.number.isRequired,
    noPerPage: PropTypes.number.isRequired,
    posts: Posts,
    tags: Tags,
    users: Users
  };

  componentDidMount() {
    Prism.highlightAll();
  }

  findUserByUserId(userId) {
    return this.props.users.find(user => user.id === userId);
  }

  findTagsByPost(post) {
    return post.tagIds.map(tagId =>
      this.props.tags.find(tag => tag.id === tagId)
    );
  }

  renderArticles() {
    if (this.props.posts.length === 0) {
      return <h2>No articles currently exist</h2>;
    }

    return this.props.posts.map(post => {
      const user = this.findUserByUserId(post.userId);

      return (
        <Article
          key={post.id}
          post={post}
          user={user}
          showMore
          tags={this.findTagsByPost(post)}
        />
      );
    });
  }

  render() {
    return this.renderArticles();
  }
}

export class BlogAside extends Component {
  static propTypes = {
    archive: Archive,
    tagId: PropTypes.number,
    tagSummary: TagSummaryProps
  };

  static defaultProps = {
    tagId: null
  };

  renderArchive() {
    return (
      <Fragment>
        <h3>Past Blog Entries</h3>
        <BlogArchive
          icon={'icon-calendar'}
          list={this.props.archive}
          urlPath={'blog'}
        />
      </Fragment>
    );
  }

  renderTagSummary() {
    return (
      <Fragment>
        <h3>Blog Tags</h3>
        <TagSummary
          highlightTagId={this.props.tagId}
          icon={'icon-tag'}
          list={this.props.tagSummary}
          urlPath={'blog/t'}
        />
      </Fragment>
    );
  }

  render() {
    return (
      <Fragment>
        {this.renderArchive()}
        {this.renderTagSummary()}
      </Fragment>
    );
  }
}
