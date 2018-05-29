import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';
import Prism from 'prismjs';
import 'prismjs/plugins/line-numbers/prism-line-numbers';

import { Archive, Posts } from '@anujnair/js/types/post';
import { Tags, TagSummary as TagSummaryProps } from '@anujnair/js/types/tag';
import { Users } from '@anujnair/js/types/user';

import Post from '@anujnair/js/components/post/post';
import BlogArchive from '@anujnair/js/components/blog-archive/blog-archive';
import TagSummary from '@anujnair/js/components/tag-summary/tag-summary';
import Pagination from '@anujnair/js/components/pagination/pagination';

export default class BlogIndex extends Component {
  static propTypes = {
    count: PropTypes.number.isRequired,
    noPerPage: PropTypes.number.isRequired,
    page: PropTypes.number.isRequired,
    posts: Posts.isRequired,
    tags: Tags.isRequired,
    users: Users.isRequired
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

  renderPosts() {
    if (this.props.posts.length === 0) {
      return <h2>No articles currently exist</h2>;
    }

    return this.props.posts.map(post => {
      const user = this.findUserByUserId(post.userId);

      return (
        <Post
          key={post.id}
          post={post}
          showMore
          tags={this.findTagsByPost(post)}
          user={user}
        />
      );
    });
  }

  render() {
    return (
      <Fragment>
        {this.renderPosts()}
        <Pagination
          currentPage={this.props.page}
          maxResults={this.props.count}
          noPerPage={this.props.noPerPage}
          slug={window.location.pathname}
        />
      </Fragment>
    );
  }
}

export class BlogAside extends Component {
  static propTypes = {
    archive: Archive.isRequired,
    tagId: PropTypes.number,
    tagSummary: TagSummaryProps.isRequired
  };

  static defaultProps = {
    tagId: null
  };

  renderArchive() {
    return (
      <BlogArchive
        icon="icon-calendar"
        list={this.props.archive}
        urlPath="blog"
      />
    );
  }

  renderTagSummary() {
    return (
      <TagSummary
        header="Blog"
        highlightTagId={this.props.tagId}
        icon="icon-tag"
        list={this.props.tagSummary}
        urlPath="blog/t"
      />
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
