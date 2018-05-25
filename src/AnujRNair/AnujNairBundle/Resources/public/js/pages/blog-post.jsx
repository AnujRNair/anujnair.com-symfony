import React, { Component } from 'react';
import Prism from 'prismjs';
import 'prismjs/plugins/line-numbers/prism-line-numbers';

import { Post as PostProps, Similar } from '@anujnair/js/types/post';
import { Tags } from '@anujnair/js/types/tag';
import { Users } from '@anujnair/js/types/user';

import Post from '@anujnair/js/components/post/post.jsx';
import SimilarPosts from '@anujnair/js/components/similar-posts/similar-posts.jsx';

export default class BlogPost extends Component {
  static propTypes = {
    blog: PostProps,
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

  render() {
    return (
      <Post
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
    similar: Similar
  };

  render() {
    return (
      <SimilarPosts
        icon={'icon-calendar'}
        list={this.props.similar}
        urlPath={'blog'}
      />
    );
  }
}
