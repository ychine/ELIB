# UI Component Library

A modular Vue 3 component library for ISU StudyGo system.

## Components

### UniversalSidebar
A universal, responsive, collapsible sidebar component that can be used across all pages.

**Props:**
- `menuItems` (Array, required): Array of menu items with structure:
  ```js
  {
    key: 'home',
    label: 'Home',
    href: '/home',
    icon: '/path/to/icon.png', // or iconClass: 'fas fa-home'
  }
  ```
- `activeRoute` (String): Current active route key
- `user` (Object): User object with `name`, `email`, `avatar`, `campus`
- `logos` (Object): Logo paths `{ border: '/path', solid: '/path' }`
- `defaultExpanded` (Boolean): Whether sidebar starts expanded

**Usage:**
```vue
<UniversalSidebar
  :menu-items="menuItems"
  active-route="home"
  :user="user"
  :logos="logos"
  :default-expanded="false"
/>
```

### AccountSettingsOverlay
An overlay panel for account settings that opens when profile is clicked.

**Props:**
- `user` (Object, required): User object

**Events:**
- `close`: Emitted when overlay is closed
- `save`: Emitted when save is clicked with form data

### UiInput
Standardized input field component.

**Props:**
- `modelValue` (String|Number): v-model value
- `type` (String): Input type (text, email, password, etc.)
- `placeholder` (String): Placeholder text
- `disabled` (Boolean): Disabled state
- `required` (Boolean): Required field
- `error` (String): Error message

**Usage:**
```vue
<UiInput
  v-model="email"
  type="email"
  placeholder="Enter email"
  :error="emailError"
/>
```

### UiButton
Standardized button component with multiple variants.

**Props:**
- `variant` (String): 'primary', 'secondary', 'danger', 'outline'
- `type` (String): Button type
- `disabled` (Boolean): Disabled state
- `loading` (Boolean): Loading state
- `loadingText` (String): Text to show while loading
- `fullWidth` (Boolean): Full width button

**Usage:**
```vue
<UiButton variant="primary" :loading="isLoading" @click="handleClick">
  Save Changes
</UiButton>
```

### UiTable
Standardized table component.

**Props:**
- `headers` (Array): Array of header strings
- `data` (Array): Table data
- `striped` (Boolean): Striped rows
- `bordered` (Boolean): Bordered table
- `emptyText` (String): Text when no data

**Usage:**
```vue
<UiTable :headers="['Name', 'Email', 'Role']" :striped="true">
  <template #body>
    <tr v-for="user in users" :key="user.id">
      <td>{{ user.name }}</td>
      <td>{{ user.email }}</td>
      <td>{{ user.role }}</td>
    </tr>
  </template>
</UiTable>
```

### UiCard
Reusable card component.

**Props:**
- `hover` (Boolean): Enable hover effect
- `shadow` (Boolean): Enable shadow

**Slots:**
- `default`: Card body content
- `header`: Card header
- `footer`: Card footer

**Usage:**
```vue
<UiCard :hover="true">
  <template #header>
    <h3>Card Title</h3>
  </template>
  <p>Card content</p>
  <template #footer>
    <UiButton>Action</UiButton>
  </template>
</UiCard>
```

## Integration

Components are automatically registered when using the sidebar root element. For standalone usage, import from the index:

```js
import { UiButton, UiInput, UiCard } from '@/components/ui';
```




