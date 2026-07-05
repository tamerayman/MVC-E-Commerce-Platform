window.onload = function() {
  //<editor-fold desc="Changeable Configuration Block">

  // the following lines will be replaced by docker/configurator, when it runs in a docker-container
  
  // Request interceptor to include credentials
  const requestInterceptor = (request) => {
    request.headers = request.headers || {};
    request.headers['X-Requested-With'] = 'XMLHttpRequest';
    return request;
  };

  window.ui = SwaggerUIBundle({
    url: "../swagger.json",
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: "StandaloneLayout",
    requestInterceptor: requestInterceptor
  });

  // Override the execute method to include credentials
  setTimeout(() => {
    const originalExecute = window.ui.specActions.execute;
    if (originalExecute) {
      window.ui.specActions.execute = function(options) {
        options = options || {};
        if (!options.requestInterceptor) {
          options.requestInterceptor = requestInterceptor;
        }
        return originalExecute.call(this, options);
      };
    }
  }, 100);

  //</editor-fold>
};
