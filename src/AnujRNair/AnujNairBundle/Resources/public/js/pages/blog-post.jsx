import React, { Component } from 'react';
import Prism from 'prismjs';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-php';
import 'prismjs/plugins/line-numbers/prism-line-numbers';

import { Post as PostProps, Similar } from '@anujnair/js/types/post';
import { Tags } from '@anujnair/js/types/tag';
import { Users } from '@anujnair/js/types/user';

import Post from '@anujnair/js/components/post/post';
import SimilarPosts from '@anujnair/js/components/similar-posts/similar-posts';

export default class BlogPost extends Component {
  static propTypes = {
    blog: PostProps.isRequired,
    tags: Tags.isRequired,
    users: Users.isRequired
  };

  componentDidMount() {
    Prism.highlightAll();
  }

  shouldComponentUpdate() {
    return false;
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
        showMore={false}
        tags={this.findTagsByPost(this.props.blog)}
        user={this.findUserByUserId(this.props.blog.userId)}
      />
    );
  }
}

const BlogAside = ({ similar }) => (
  <SimilarPosts icon="icon-calendar" list={similar} urlPath="blog" />
);

BlogAside.propTypes = {
  similar: Similar.isRequired
};

export { BlogAside };
