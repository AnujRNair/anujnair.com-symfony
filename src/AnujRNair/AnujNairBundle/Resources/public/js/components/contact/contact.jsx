import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import { ContactForm } from '@anujnair/js/types/form';

import './contact.scss';

export default class Contact extends Component {
  static propTypes = {
    form: ContactForm,
    success: PropTypes.arrayOf(PropTypes.string.isRequired)
  };

  constructor(props) {
    super(props);

    this.state = {
      name: props.form.values.name || '',
      email: props.form.values.email || '',
      subject: props.form.values.subject || '',
      contents: props.form.values.contents || ''
    };
  }

  updateContactFieldState = (prop, text) => {
    this.setState(() => ({
      [prop]: text
    }));
  };

  handleNameChange = e => this.updateContactFieldState('name', e.target.value);
  handleEmailChange = e =>
    this.updateContactFieldState('email', e.target.value);
  handleSubjectChange = e =>
    this.updateContactFieldState('subject', e.target.value);
  handleContentsChange = e =>
    this.updateContactFieldState('contents', e.target.value);

  hasError(section) {
    return (
      typeof this.props.form.errors[section] !== 'undefined' &&
      this.props.form.errors[section].length > 0
    );
  }

  renderError(section) {
    if (!this.hasError(section)) {
      return null;
    }

    const errors = this.props.form.errors[section].map(item => (
      <li key={item}>{item}</li>
    ));

    return <ul className={'contact__error'}>{errors}</ul>;
  }

  renderSuccess() {
    if (this.props.success.length === 0) {
      return null;
    }

    return <div className={'contact__success'}>{this.props.success[0]}</div>;
  }

  render() {
    return (
      <Fragment>
        <h3 id={'contact-me'}>Let&apos;s Connect!</h3>
        {this.renderSuccess()}
        <form
          className={'contact'}
          action={'/about/'}
          name={'anujnair_contact_form'}
          method={'post'}
        >
          {this.renderError('name')}
          <label
            className={`contact__group ${
              this.hasError('name') ? 'contact__group--error' : ''
            }`}
            htmlFor={'anujnair_contact_form[name]'}
          >
            <span className={'icon icon-man'} />
            <input
              type={'text'}
              id={'anujnair_contact_form[name]'}
              name={'anujnair_contact_form[name]'}
              placeholder={'Name'}
              onChange={this.handleNameChange}
              value={this.state.name}
            />
          </label>

          {this.renderError('email')}
          <label
            className={`contact__group ${
              this.hasError('email') ? 'contact__group--error' : ''
            }`}
            htmlFor={'anujnair_contact_form[email]'}
          >
            <span className={'icon icon-mail-full'} />
            <input
              type={'text'}
              id={'anujnair_contact_form[email]'}
              name={'anujnair_contact_form[email]'}
              placeholder={'Email'}
              onChange={this.handleEmailChange}
              value={this.state.email}
            />
          </label>

          {this.renderError('subject')}
          <label
            className={`contact__group ${
              this.hasError('subject') ? 'contact__group--error' : ''
            }`}
            htmlFor={'anujnair_contact_form[subject]'}
          >
            <span className={'icon icon-pencil-full'} />
            <input
              type={'text'}
              id={'anujnair_contact_form[subject]'}
              name={'anujnair_contact_form[subject]'}
              placeholder={'Subject'}
              onChange={this.handleSubjectChange}
              value={this.state.subject}
            />
          </label>

          {this.renderError('contents')}
          <label
            className={`contact__group ${
              this.hasError('contents') ? 'contact__group--error' : ''
            }`}
            htmlFor={'anujnair_contact_form[contents]'}
          >
            <span className={'icon icon-chats-full'} />
            <textarea
              id={'anujnair_contact_form[contents]'}
              name={'anujnair_contact_form[contents]'}
              rows={4}
              placeholder={'Write message here'}
              onChange={this.handleContentsChange}
              value={this.state.contents}
            />
          </label>

          <input
            type={'hidden'}
            name={'anujnair_contact_form[_token]'}
            value={this.props.form.csrf}
          />

          <button
            className={'contact__submit'}
            type={'submit'}
            name={'anujnair_contact_form[send]'}
          >
            Send
            <span className={'icon icon-plane'} />
          </button>
        </form>
      </Fragment>
    );
  }
}
