import { useState } from 'react';
import HeaderBar from '../../components/HeaderBar';
import Sidebar from '../../components/Sidebar';
import { Card, Button, Avatar, Breadcrumb, Input, DatePicker, Select, List, Upload, message, Divider, Row, Col, Tag } from 'antd';
import type { RcFile } from 'antd/es/upload';
import { ArrowLeftOutlined, EditOutlined, PaperClipOutlined, CommentOutlined, ClockCircleOutlined, SyncOutlined, CheckCircleOutlined, UserOutlined } from '@ant-design/icons';
import dayjs from 'dayjs';
import ReactQuill from 'react-quill';
import 'react-quill/dist/quill.snow.css';
import { useTranslation } from 'react-i18next';

const users = [
  { label: 'Nguyễn Văn A', value: 'Nguyễn Văn A' },
  { label: 'Trần Thị B', value: 'Trần Thị B' },
  { label: 'Lê Văn C', value: 'Lê Văn C' },
];

type TaskStatus = 'Chưa bắt đầu' | 'Đang làm' | 'Hoàn thành';

const statusMap: Record<TaskStatus, { color: string; icon: React.ReactNode }> = {
  'Chưa bắt đầu': { color: 'default', icon: <ClockCircleOutlined /> },
  'Đang làm': { color: 'blue', icon: <SyncOutlined spin /> },
  'Hoàn thành': { color: 'green', icon: <CheckCircleOutlined /> },
};

type CommentFile = { name: string; url: string; type: string };
type CommentType = {
  user: string;
  content: string;
  time: string;
  files?: CommentFile[];
};

export default function TaskDetail() {
  // State cho các trường chỉnh sửa trực tiếp
  const [name, setName] = useState('Thiết kế giao diện');
  const [editingName, setEditingName] = useState(false);
  const [assignees, setAssignees] = useState(['Nguyễn Văn A']);
  const [status, setStatus] = useState<TaskStatus>('Đang làm');
  const [due, setDue] = useState('2024-06-10');
  const [description, setDescription] = useState('Thiết kế UI/UX cho trang dashboard và các module chính.');
  const [logtimes, setLogtimes] = useState([
    { user: 'Nguyễn Văn A', hours: 2, desc: 'Thiết kế UI', time: '10/06/2024 10:00' },
    { user: 'Trần Thị B', hours: 1, desc: 'Review', time: '11/06/2024 14:00' },
  ]);
  const [logtimeUser, setLogtimeUser] = useState(users[0].value);
  const [logtime, setLogtime] = useState('');
  const [logtimeDesc, setLogtimeDesc] = useState('');
  const [attachments, setAttachments] = useState([
    { name: 'wireframe.pdf', url: '#' },
    { name: 'logo.png', url: '#' },
  ]);
  const [comments, setComments] = useState<CommentType[]>([
    {
      user: 'Trần Thị B',
      content: 'Nhớ bám sát guideline nhé!',
      time: '1 giờ trước',
      files: [
        { name: 'design.png', url: '/path/to/design.png', type: 'image/png' },
        { name: 'spec.pdf', url: '/path/to/spec.pdf', type: 'application/pdf' },
      ],
    },
    {
      user: 'Nguyễn Văn A',
      content: 'Đã cập nhật bản mới.',
      time: '10 phút trước',
      // Không có files cũng được
    },
  ]);
  const [commentInput, setCommentInput] = useState('');

  const { t } = useTranslation();

  // Hàm upload file (giả lập)
  const handleUpload = (file: RcFile) => {
    setAttachments([...attachments, { name: file.name, url: '#' }]);
    message.success('Tải lên thành công!');
    return false;
  };

  // Hàm thêm logtime mới
  const handleAddLogtime = () => {
    if (!logtime || !logtimeUser) return;
    setLogtimes([
      ...logtimes,
      {
        user: logtimeUser,
        hours: Number(logtime),
        desc: logtimeDesc,
        time: new Date().toLocaleString('vi-VN'),
      },
    ]);
    setLogtime('');
    setLogtimeDesc('');
  };

  // Hàm thêm bình luận
  const handleAddComment = () => {
    if (commentInput.trim()) {
      setComments([...comments, { user: 'Bạn', content: commentInput, time: 'Vừa xong' }]);
      setCommentInput('');
    }
  };

  const statusOptions = [
    {
      label: (
        <span>
          <ClockCircleOutlined style={{ color: '#888' }} /> {t('notStarted')}
        </span>
      ),
      value: t('notStarted'),
    },
    {
      label: (
        <span>
          <SyncOutlined spin style={{ color: '#1890ff' }} /> {t('inProgress')}
        </span>
      ),
      value: t('inProgress'),
    },
    {
      label: (
        <span>
          <CheckCircleOutlined style={{ color: '#52c41a' }} /> {t('done')}
        </span>
      ),
      value: t('done'),
    },
  ];

  return (
    <div style={{ display: 'flex', height: '100vh', flexDirection: 'column' }}>
      <HeaderBar />
      <div style={{ display: 'flex', flex: 1 }}>
        <Sidebar />
        <div style={{ flex: 1, background: '#f6f8fa', overflow: 'auto', padding: '32px 0' }}>
          <div style={{ margin: '0 auto', padding: '0 40px'}}>
            <Breadcrumb>
              <Breadcrumb.Item>
                <a href="/projects/1"><ArrowLeftOutlined /> {t('project')} ABC</a>
              </Breadcrumb.Item>
              <Breadcrumb.Item>{t('task')}: {name}</Breadcrumb.Item>
            </Breadcrumb>
            <Card
              style={{
                borderRadius: 12,
                boxShadow: '0 2px 8px #0001',
                border: 'none',
                marginTop: 24,
                marginBottom: 24,
              }}
              bodyStyle={{ padding: 32 }}
            >
              <div style={{ display: 'flex', gap: 32, alignItems: 'flex-start' }}>
                {/* Cột trái: Thông tin chính */}
                <div style={{ flex: 2 }}>
                  <div style={{ display: 'flex', alignItems: 'center', marginBottom: 16 }}>
                    {editingName ? (
                      <Input
                        value={name}
                        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setName(e.target.value)}
                        onBlur={() => setEditingName(false)}
                        onPressEnter={() => setEditingName(false)}
                        style={{ fontSize: 24, fontWeight: 700, width: 400 }}
                        autoFocus
                        placeholder={t('taskName')}
                      />
                    ) : (
                      <h1 style={{ margin: 0, fontWeight: 700, fontSize: 24, color: '#222', cursor: 'pointer' }} onClick={() => setEditingName(true)}>
                        {name} <EditOutlined style={{ fontSize: 16, color: '#aaa' }} />
                      </h1>
                    )}
                  </div>
                  <div style={{ marginBottom: 24 }}>
                    <Row gutter={24}>
                      <Col span={12}>
                        <b><UserOutlined style={{ color: '#4B48E5' }} /> {t('assignees')}</b>
                        <Select
                          mode="multiple"
                          value={assignees}
                          onChange={setAssignees}
                          options={users}
                          style={{ width: '100%', marginTop: 8 }}
                          placeholder={t('selectAssignees')}
                        />
                      </Col>
                      <Col span={6}>
                        <b>
                          <span style={{ color: statusMap[status as TaskStatus].color }}>
                            {statusMap[status as TaskStatus].icon}
                          </span> {t('status')}
                        </b>
                        <Select
                          value={status}
                          onChange={setStatus}
                          options={statusOptions}
                          style={{ width: '100%', marginTop: 8 }}
                        />
                      </Col>
                      <Col span={6}>
                        <b><ClockCircleOutlined style={{ color: '#faad14' }} /> {t('dueDate')}</b>
                        <DatePicker
                          value={due ? dayjs(due) : null}
                          onChange={(d: dayjs.Dayjs | null) => setDue(d ? d.format('YYYY-MM-DD') : '')}
                          format="DD/MM/YYYY"
                          style={{ width: '100%', marginTop: 8 }}
                        />
                      </Col>
                    </Row>
                  </div>
                  <div style={{ marginBottom: 24 }}>
                    <b>{t('description')}</b>
                    <ReactQuill
                      value={description}
                      onChange={setDescription}
                      style={{ marginTop: 8, background: '#fff', borderRadius: 8, height: 300 }}
                      placeholder={t('taskDescriptionPlaceholder')}
                      theme="snow"
                    />
                  </div>
                  <Divider />
                  <div style={{ marginBottom: 16 }}>
                    <b><ClockCircleOutlined /> {t('logtime')}</b>
                    <div style={{ display: 'flex', gap: 8, marginTop: 8, marginBottom: 8 }}>
                      <Select
                        value={logtimeUser}
                        onChange={setLogtimeUser}
                        options={users}
                        style={{ width: 180 }}
                        placeholder={t('logUser')}
                      />
                      <Input
                        value={logtime}
                        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setLogtime(e.target.value)}
                        placeholder={t('logHours')}
                        style={{ width: 100 }}
                        type="number"
                        min={0}
                      />
                      <Input
                        value={logtimeDesc}
                        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setLogtimeDesc(e.target.value)}
                        placeholder={t('logDesc')}
                        style={{ width: 220 }}
                      />
                      <Button type="primary" onClick={handleAddLogtime}>{t('log')}</Button>
                    </div>
                    <List
                      dataSource={logtimes}
                      renderItem={(item) => (
                        <Card
                          size="small"
                          style={{
                            marginBottom: 12,
                            background: '#f6f8fa',
                            borderLeft: '4px solid #4B48E5',
                            borderRadius: 8,
                            boxShadow: '0 1px 4px #0001',
                          }}
                          bodyStyle={{ padding: 12 }}
                        >
                          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                            <Avatar style={{ background: '#4B48E5' }}>{item.user[0]}</Avatar>
                            <div style={{ flex: 1 }}>
                              <div style={{ fontWeight: 600 }}>
                                <ClockCircleOutlined style={{ color: '#faad14', marginRight: 4 }} />
                                {item.hours} giờ
                                <span style={{ color: '#888', marginLeft: 8 }}>{item.desc}</span>
                              </div>
                              <div style={{ fontSize: 12, color: '#aaa' }}>
                                {item.user} • {item.time}
                              </div>
                            </div>
                          </div>
                        </Card>
                      )}
                      locale={{ emptyText: 'Chưa có logtime.' }}
                      style={{ marginTop: 8 }}
                    />
                  </div>
                  <Divider />
                  <div>
                    <b><PaperClipOutlined /> {t('attachments')}</b>
                    <Upload
                      showUploadList={false}
                      beforeUpload={handleUpload}
                    >
                      <Button size="small" style={{ marginLeft: 8 }}>{t('upload')}</Button>
                    </Upload>
                    <List
                      dataSource={attachments}
                      renderItem={file => (
                        <List.Item>
                          <PaperClipOutlined style={{ marginRight: 8 }} />
                          <a href={file.url}>{file.name}</a>
                        </List.Item>
                      )}
                      locale={{ emptyText: 'Không có tệp đính kèm.' }}
                      size="small"
                      style={{ marginTop: 8 }}
                    />
                  </div>
                </div>
                {/* Cột phải: Bình luận, lịch sử */}
                <div style={{ flex: 1 }}>
                  <Card
                    style={{
                      borderRadius: 12,
                      boxShadow: '0 2px 8px #0001',
                      border: 'none',
                      marginBottom: 24,
                    }}
                    bodyStyle={{ padding: 24 }}
                    title={<span><CommentOutlined /> {t('comments')}</span>}
                  >
                    <List
                      dataSource={comments}
                      renderItem={(cmt) => (
                        <List.Item>
                          <List.Item.Meta
                            avatar={<Avatar style={{ background: '#4B48E5' }}>{cmt.user[0]}</Avatar>}
                            title={
                              <span>
                                <b>{cmt.user}</b>
                                <span style={{ color: '#aaa', fontWeight: 400, fontSize: 12, marginLeft: 8 }}>
                                  <ClockCircleOutlined style={{ marginRight: 4 }} />
                                  {cmt.time}
                                </span>
                              </span>
                            }
                            description={
                              <div>
                                <CommentOutlined style={{ color: '#1890ff', marginRight: 4 }} />
                                {cmt.content}
                                {/* Hiển thị file/ảnh nếu có */}
                                {cmt.files && cmt.files.length > 0 && (
                                  <div style={{ marginTop: 8 }}>
                                    {cmt.files.map((file: CommentFile, idx: number) =>
                                      file.type.startsWith('image') ? (
                                        <img key={idx} src={file.url} alt={file.name} style={{ maxWidth: 80, marginRight: 8, borderRadius: 4 }} />
                                      ) : (
                                        <a key={idx} href={file.url} style={{ marginRight: 8 }}>
                                          <PaperClipOutlined /> {file.name}
                                        </a>
                                      )
                                    )}
                                  </div>
                                )}
                              </div>
                            }
                          />
                        </List.Item>
                      )}
                      locale={{ emptyText: 'Chưa có bình luận.' }}
                    />
                    <ReactQuill
                      value={commentInput}
                      onChange={setCommentInput}
                      style={{
                        marginTop: 16,
                        background: '#fff',
                        borderRadius: 8,
                        minHeight: 120,   // hoặc height: 150
                        height: 150
                      }}
                      placeholder="Nhập bình luận, có thể chèn ảnh, file..."
                      theme="snow"
                    />
                    <div style={{ textAlign: 'right', marginTop: 8 }}>
                      <Button type="primary" onClick={handleAddComment}>{t('sendComment')}</Button>
                    </div>
                  </Card>
                  <Card
                    style={{
                      borderRadius: 12,
                      boxShadow: '0 2px 8px #0001',
                      border: 'none',
                    }}
                    bodyStyle={{ padding: 24 }}
                    title={t('activityHistory')}
                  >
                    <List
                      dataSource={[
                        { user: 'Nguyễn Văn A', action: 'cập nhật trạng thái task', time: '10 phút trước' },
                        { user: 'Trần Thị B', action: 'log 2 giờ', time: '1 giờ trước' },
                      ]}
                      renderItem={(item: { user: string; action: string; time: string }) => (
                        <List.Item>
                          <List.Item.Meta
                            avatar={<Avatar>{item.user[0]}</Avatar>}
                            title={<span><b>{item.user}</b> {item.action}</span>}
                            description={item.time}
                          />
                        </List.Item>
                      )}
                      locale={{ emptyText: 'Chưa có lịch sử.' }}
                    />
                  </Card>
                </div>
              </div>
            </Card>
          </div>
        </div>
      </div>
    </div>
  );
}
