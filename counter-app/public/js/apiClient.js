class ApiClient {
  constructor(options = {}) {
    this.csrfToken = options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    this.isLoading = false;
    this.defaultOptions = {
      headers: {
        'X-CSRF-TOKEN': this.csrfToken,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    };
  }

  async post(url, data = {}, options = {}) {
    return this.request(url, {
      method: 'POST',
      body: JSON.stringify(data),
      ...options
    });
  }

  async get(url, options = {}) {
    return this.request(url, {
      method: 'GET',
      cache: 'no-store',
      ...options
    });
  }

  async request(url, options = {}) {
    if (this.isLoading) return Promise.reject(new Error('Request already in progress'));

    this.isLoading = true;

    if (typeof this.onLoadingStart === 'function') {
      this.onLoadingStart();
    }

    try {
      const mergedOptions = {
        ...this.defaultOptions,
        ...options,
        headers: {
          ...this.defaultOptions.headers,
          ...(options.headers || {})
        }
      };

      const response = await fetch(url, mergedOptions);

      if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      if (typeof this.onError === 'function') {
        this.onError(error);
      }
      throw error;
    } finally {
      this.isLoading = false;

      if (typeof this.onLoadingEnd === 'function') {
        this.onLoadingEnd();
      }
    }
  }

  setLoadingStartCallback(callback) {
    this.onLoadingStart = callback;
    return this;
  }

  setLoadingEndCallback(callback) {
    this.onLoadingEnd = callback;
    return this;
  }

  setErrorCallback(callback) {
    this.onError = callback;
    return this;
  }
}

if (typeof module !== 'undefined' && module.exports) {
  module.exports = ApiClient;
} else {
  window.ApiClient = ApiClient;
}