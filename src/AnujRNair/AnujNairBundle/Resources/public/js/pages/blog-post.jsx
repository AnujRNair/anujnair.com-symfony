import React, { Component, Fragment } from 'react';

import { Post, Similar } from '@anujnair/js/types/post';
import { Tags } from '@anujnair/js/types/tag';
import { Users } from '@anujnair/js/types/user';

import Article from '@anujnair/js/components/article/article.jsx';
import SimilarPosts from '@anujnair/js/components/similar-posts/similar-posts.jsx';

export default class BlogPost extends Component {
  static propTypes = {
    blog: Post,
    tags: Tags,
    users: Users
  };

  findUserByUserId(userId) {
    return this.props.users.find(user => user.id === userId);
  }

  findTagsByPost(post) {
    return post.tagIds.map(tagId =>
      this.props.tags.find(tag => tag.id === tagId)
    );
  }

  render() {
    return (
      <Article
        post={this.props.blog}
        user={this.findUserByUserId(this.props.blog.userId)}
        showMore={false}
        tags={this.findTagsByPost(this.props.blog)}
      />
    );
  }
}

export class BlogAside extends Component {
  static propTypes = {
    similarBlogPosts: Similar
  };

  render() {
    return <SimilarPosts icon={'icon-calendar'} list={this.props.similarBlogPosts} urlPath={'blog'} />;
  }
}
