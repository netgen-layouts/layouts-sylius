const handlePostRequest = async (componentIdentifier, componentId) => {
  const data = localStorage.getItem(`nglayouts-sylius-component-draft-${componentIdentifier}`);
  if (!data) return;

  const { blockId, locale } = JSON.parse(data);

  const nglayoutsBasePathElement = document.querySelector('[name="nglayouts-base-path"]');
  const nglayoutsBasePath = nglayoutsBasePathElement && nglayoutsBasePathElement.getAttribute('content');

  const url = `${nglayoutsBasePath}admin/sylius/blocks/${blockId}/${locale}/connect-component/${componentIdentifier}/${componentId}`;

  const bc = new BroadcastChannel('publish_content');

  await fetch(url, { method: 'post' })
    .then(() => {
      bc.postMessage(JSON.parse(data));
    })
    .then(() => {
      bc.close();
      localStorage.removeItem(`nglayouts-sylius-component-draft-${componentIdentifier}`);
    });
};

const saveDataToLocalStorage = (hash, componentIdentifier) => {
  if (!hash.includes('#ngl-sylius-component/')) return;

  const params = hash.replace('#ngl-sylius-component/', '').split('/');
  const blockId = params[0];
  const locale = params[1];
  const componentIdentifierFromHash = params[2];

  if (componentIdentifier !== componentIdentifierFromHash) return;

  const data = { blockId, locale, componentIdentifier };
  localStorage.setItem(`nglayouts-sylius-component-draft-${componentIdentifier}`, JSON.stringify(data));
};

const connectBlockAndContent = async () => {
  const urlHash = window.location.hash;

  const syliusComponentDraftIdentifierElement = document.querySelector('[name="nglayouts-sylius-component-initialize-create-identifier"]');
  const syliusComponentDraftIdentifier = syliusComponentDraftIdentifierElement && syliusComponentDraftIdentifierElement.getAttribute('content');

  if (syliusComponentDraftIdentifier) {
    saveDataToLocalStorage(urlHash, syliusComponentDraftIdentifier);

    return;
  }

  const indexComponentIdentifierElement = document.querySelector('[name="nglayouts-sylius-component-index-identifier"]');
  const indexComponentIdentifier = indexComponentIdentifierElement && indexComponentIdentifierElement.getAttribute('content');

  const showComponentIdentifierElement = document.querySelector('[name="nglayouts-sylius-component-show-identifier"]');
  const showComponentIdentifier = showComponentIdentifierElement && showComponentIdentifierElement.getAttribute('content');

  const createdComponentIdentifier = indexComponentIdentifier || showComponentIdentifier;

  const indexComponentIdElement = document.querySelector('[name="nglayouts-sylius-component-index-selected-id"]');
  const indexComponentId = indexComponentIdElement && indexComponentIdElement.getAttribute('content');

  const showComponentIdElement = document.querySelector('[name="nglayouts-sylius-component-show-id"]');
  const showComponentId = showComponentIdElement && showComponentIdElement.getAttribute('content');

  const createdComponentId = indexComponentId || showComponentId;

  if (createdComponentIdentifier && createdComponentId) {
    handlePostRequest(createdComponentIdentifier, createdComponentId);
  }
};

window.addEventListener('DOMContentLoaded', () => {
  connectBlockAndContent();
});
